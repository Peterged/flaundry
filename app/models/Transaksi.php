<?php

namespace App\models;

use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
use App\Libraries\Request;
use App\Services\FlashMessage as fm;
use App\Utils\MyLodash as _;



class Transaksi extends Model
{
    public int $id;
    public int $id_outlet;
    public string $kode_invoice;
    public int $id_member;
    public string $tgl;
    public string $batas_waktu;
    public string $tgl_bayar;
    public float $biaya_tambahan;
    public float $diskon;
    public float $pajak;
    public string $status;
    public string $dibayar;
    public int $id_user;


    #[Table('tb_transaksi')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        $this->setRequiredProperties(['id_outlet', 'kode_invoice', 'id_member', 'tgl', 'batas_waktu', 'tgl_bayar', 'biaya_tambahan', 'diskon', 'pajak', 'status', 'dibayar', 'id_user']);
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $this->setRequiredProperties(['id_outlet', 'kode_invoice', 'id_member', 'tgl', 'batas_waktu', 'tgl_bayar', 'biaya_tambahan', 'diskon', 'pajak', 'status', 'dibayar', 'id_user']);

        $result = new SaveResult();
        
        $this->validateSave();

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO tb_transaksi (id_outlet, kode_invoice, id_member, tgl, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, status, dibayar, id_user) VALUES (:id_outlet, :kode_invoice, :id_member, :tgl, :batas_waktu, :tgl_bayar, :biaya_tambahan, :diskon, :pajak, :status, :dibayar, :id_user)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'id_outlet' => $this->id_outlet,
                'kode_invoice' => $this->kode_invoice,
                'id_member' => $this->id_member,
                'tgl' => $this->tgl,
                'batas_waktu' => $this->batas_waktu,
                'tgl_bayar' => $this->tgl_bayar,
                'biaya_tambahan' => $this->biaya_tambahan,
                'diskon' => $this->diskon,
                'pajak' => $this->pajak,
                'status' => $this->status,
                'dibayar' => $this->dibayar,
                'id_user' => $this->id_user
            ]);
            
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        }, true, false);

        $result->setSuccess(true);
        return $result;
    }

    public function validateSave(array | null $body = null, string $flashMessageContext = "detail_transaksi_message")
    {
        $invoiceFormatRegex = "/^INV\/\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d+$/";
        $statusList = ['baru', 'proses', 'selesai', 'diambil'];
        $dibayarList = ['dibayar', 'belum_dibayar'];

        try {
            if(!is_null($body)) {
                $this->id_outlet = $body['id_outlet'];
                $this->kode_invoice = $body['kode_invoice'];
                $this->id_member = $body['id_member'];
                $this->tgl = $body['tgl'];
                $this->batas_waktu = $body['batas_waktu'];
                $this->tgl_bayar = $body['tgl_bayar'];
                $this->biaya_tambahan = $body['biaya_tambahan'];
                $this->diskon = $body['diskon'];
                $this->pajak = $body['pajak'];
                $this->status = $body['status'];
                $this->dibayar = $body['dibayar'];
                $this->id_user = $body['id_user'];
            }
            
            if(!v::number()->min(0)->validate($this->id_outlet)) {
                throw new ValidationException('Id outlet harus berupa angka dan lebih dari 0!', FLASH_ERROR);
            }
            if(!v::stringType()->regex($invoiceFormatRegex)->validate($this->kode_invoice)) {
                throw new ValidationException('Kode invoice tidak sesuai format! INV/YYYY/MM/DD/NOMOR_TRANSAKSI', FLASH_ERROR);
            }
            if(!v::number()->min(0)->validate($this->id_member)) {
                throw new ValidationException('Id member harus berupa angka dan lebih dari 0!', FLASH_ERROR);
            }
            if(!v::date()->validate($this->tgl)) {
                throw new ValidationException('Tanggal harus berupa tanggal yang valid!', FLASH_ERROR);
            }

            if(!v::date()->validate($this->batas_waktu)) {
                throw new ValidationException('Batas waktu harus berupa tanggal yang valid!', FLASH_ERROR);
            }

            if(!v::date()->validate($this->tgl_bayar)) {
                throw new ValidationException('Tanggal bayar harus berupa tanggal yang valid!', FLASH_ERROR);
            }

            if(!v::number()->min(0)->validate($this->biaya_tambahan)) {
                throw new ValidationException('Biaya tambahan harus berupa angka dan tidak boleh kurang dari 0!', FLASH_ERROR);
            }

            if(!v::number()->min(0)->validate($this->diskon)) {
                throw new ValidationException('Diskon harus berupa angka dan tidak boleh kurang dari 0!', FLASH_ERROR);
            }

            if(!v::number()->min(0)->validate($this->pajak)) {
                throw new ValidationException('Pajak harus berupa angka dan tidak boleh kurang dari 0!', FLASH_ERROR);
            }

            if(!v::stringType()->in($dibayarList)->validate($this->dibayar)) {
                throw new ValidationException('Dibayar harus diantara ' . implode(', ', $dibayarList) . "!", FLASH_ERROR);
            }

            if(!v::stringType()->in($statusList)->validate($this->status)) {
                throw new ValidationException('Status harus diantara ' . implode(', ', $statusList) . "!", FLASH_ERROR);
            } 
        
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    fm::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => $flashMessageContext
                    ]);
                }
            }
            else {
                fm::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => $flashMessageContext
                ]);
            }
            
            return false;
        }

        return true;
    }
}
