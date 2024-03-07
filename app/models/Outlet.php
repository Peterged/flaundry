<?php

namespace App\models;

use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Services\FlashMessage as fm;
use App\Exceptions\ValidationException;

class Outlet extends Model
{
    public string $id;
    public string $alamat;
    public string $nama;
    public string $tlp;

    #[Table('tb_outlet')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->setRequiredProperties(['alamat', 'nama', 'tlp']);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $result = new SaveResult();
        $this->validateSave();

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO {$this->tableName} (nama, alamat, tlp) VALUES (:nama, :alamat, :tlp)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'nama' => $this->nama,
                'alamat' => $this->alamat,
                'tlp' => $this->tlp
            ]);

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        });

        $result->setSuccess(true);
        return $result;
    }

    public function validateSave(array | null $body = null)
    {
        $namaMinLength = 3;
        $namaMaxLength = 36;
        $alamatMinLength = 5;
        $alamatMaxLength = 100;
        $tlpMinLength = 10;
        $tlpMaxLength = 15;

        try {
            if(!is_null($body)) {
                $this->nama = $body['nama'];
                $this->alamat = $body['alamat'];
                $this->tlp = $body['tlp'];
            }
            if (!v::stringType()->length($namaMinLength, $namaMaxLength)->validate($this->nama)) {
                throw new ValidationException("Nama Outlet harus diantara $namaMinLength dan $namaMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->length($alamatMinLength, $alamatMaxLength)->validate($this->alamat)) {
                throw new ValidationException("Alamat harus diantara $alamatMinLength dan $alamatMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->length($tlpMinLength, $tlpMaxLength)->validate($this->tlp)) {
                throw new ValidationException("Telepon harus diantara $tlpMinLength dan $tlpMaxLength karakter", FLASH_ERROR);
            }
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    fm::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => 'outlet_message'
                    ]);
                }
            } else {
                fm::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => 'outlet_message'
                ]);
            }

            return false;
        }

        return true;
    }
}
