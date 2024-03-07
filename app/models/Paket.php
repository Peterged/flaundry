<?php

namespace App\models;

use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
use App\Libraries\Request;
use App\Services\FlashMessage as fm;
use App\Utils\MyLodash as _;



class Paket extends Model
{
    public string $id;
    public int $id_outlet;
    public string $jenis;
    public string $nama_paket;
    public float $harga;

    #[Table('tb_paket')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        $this->setRequiredProperties(['nama_paket', 'jenis', 'harga']);
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $this->setRequiredProperties(['id_outlet', 'nama_paket', 'jenis', 'harga']);

        $result = new SaveResult();
        
        $this->validateSave();

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO {$this->tableName} (id_outlet, nama_paket, jenis, harga) VALUES (:id_outlet, :nama_paket, :jenis, :harga)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'id_outlet' => $this->id_outlet,
                'nama_paket' => $this->nama_paket,
                'jenis' => $this->jenis,
                'harga' => $this->harga
            ]);
            
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        }, true, false);

        $result->setSuccess(true);
        return $result;
    }

    public function validateSave(array | null $body = null)
    {
        $namaPaketMinLength = 3;
        $namaPaketMaxLength = 36;
        $daftarJenisPaket = ['kiloan', 'selimut', 'bed_cover', 'kaos', 'lain'];

        try {
            if(!is_null($body)) {
                $this->nama_paket = $body['nama'];
                $this->jenis = $body['jenis_paket'];
                $this->harga = $body['harga'];
            }
            if (!v::stringType()->length($namaPaketMinLength, $namaPaketMaxLength)->validate($this->nama_paket)) {
                throw new ValidationException("Nama paket harus diantara $namaPaketMinLength dan $namaPaketMaxLength karakter", FLASH_ERROR);
            }
            
            if(!v::in($daftarJenisPaket)->validate($this->jenis)) {
                throw new ValidationException('Jenis paket harus diantara' . implode(', ', $daftarJenisPaket) . '!', FLASH_ERROR);
            }

            if (!v::number()->min(0)->validate($this->harga)) {
                throw new ValidationException('Harga harus berupa angka dan lebih dari 0!', FLASH_ERROR);
            }
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    fm::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => 'paket_message'
                    ]);
                }
            }
            else {
                fm::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => 'paket_message'
                ]);
            }
            
            return false;
        }

        return true;
    }
}
