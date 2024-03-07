<?php

namespace App\models;

use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
use App\Libraries\Request;
use App\Services\FlashMessage as fm;
use App\Utils\MyLodash as _;



class DetailTransaksi extends Model
{
    public int $id;
    public int $id_transaksi;
    public int $id_paket;
    public int $qty;
    public string $keterangan;
    public float $total_harga;

    #[Table('tb_detail_transaksi')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        $this->setRequiredProperties(['id_transaksi', 'id_paket', 'qty', 'keterangan', 'total_harga']);
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $this->setRequiredProperties(['id_transaksi', 'id_paket', 'qty', 'keterangan', 'total_harga']);

        $result = new SaveResult();
        
        $this->validateSave();

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO {$this->tableName} (id_transaksi, id_paket, qty, keterangan, total_harga) VALUES (:id_transaksi, :id_paket, :qty, :keterangan, :total_harga)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'id_transaksi' => $this->id_transaksi,
                'id_paket' => $this->id_paket,
                'qty' => $this->qty,
                'keterangan' => $this->keterangan,
                'total_harga' => $this->total_harga
            ]);
            
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        }, true, false);

        $result->setSuccess(true);
        return $result;
    }

    public function validateSave(array | null $body = null)
    {
        $keteranganMinLength = 0;
        $keteranganMaxLength = 100;
        $qtyMin = 1;
        $qtyMax = 9999;
        $totalHargaMin = 0;

        try {
            if(!is_null($body)) {
                $this->id_transaksi = $body['id_transaksi'];
                $this->id_paket = $body['id_paket'];
                $this->qty = $body['qty'];
                $this->keterangan = $body['keterangan'];
                $this->total_harga = $body['total_harga'];
            }
            if (!v::stringType()->length(0, $keteranganMaxLength)->validate($this->keterangan)) {
                throw new ValidationException("Keterangan berisi maksimal $keteranganMaxLength karakter", FLASH_ERROR);
            }

            if (!v::number()->min($qtyMin)->max($qtyMax)->validate($this->qty)) {
                throw new ValidationException("Qty harus diantara $qtyMin dan $qtyMax", FLASH_ERROR);
            }

            if (!v::number()->min($totalHargaMin)->validate($this->total_harga)) {
                throw new ValidationException("Total harga harus lebih dari $totalHargaMin", FLASH_ERROR);
            }

            if (!v::number()->min(0)->validate($this->id_transaksi)) {
                throw new ValidationException('Id transaksi harus berupa angka dan lebih dari 0!', FLASH_ERROR);
            }

            if (!v::number()->min(0)->validate($this->id_paket)) {
                throw new ValidationException('Id paket harus berupa angka dan lebih dari 0!', FLASH_ERROR);
            }
            
        
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    fm::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => 'detail_transaksi_message'
                    ]);
                }
            }
            else {
                fm::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => 'detail_transaksi_message'
                ]);
            }
            
            return false;
        }

        return true;
    }
}
