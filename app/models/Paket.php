<?php

namespace App\models;

use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
use App\Services\FlashMessage as fm;

class Paket extends Model
{
    private string $id;
    private int $id_outlet;
    private string $jenis;
    private string $nama_paket;
    private float $harga;

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

        $this->tryCatchWrapper(function () use ($result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO {$this->tableName} (id_outlet, nama_paket, jenis, harga) VALUES (:id_outlet, :nama_paket, :jenis, :harga)";
            $stmt = $con->prepare($sql);
            $data = $stmt->execute([
                'id_outlet' => $this->id_outlet,
                'nama_paket' => $this->nama_paket,
                'jenis' => $this->jenis,
                'harga' => $this->harga
            ]);

            $result->data = $data;
            return $result;
        }, $result);

        $result->success = true;
        return $result;
    }

    private function validateEmpty(): bool
    {
        $requiredProperties = $this->getRequiredProperties();

        if (count($requiredProperties) > 0) {
            foreach ($requiredProperties as $property) {
                if (empty($this->$property)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    private function validateSave()
    {
        try {
            if (!$this->validateEmpty()) {
                throw new ValidationException('All properties are required');
            }

            if (!v::intType()->validate($this->id_outlet)) {
                throw new ValidationException('nama_paket must be an integer');
            }

            if (!v::stringType()->min(1)->validate($this->nama_paket)) {
                throw new ValidationException('Alamat must contain atleast 1 character');
            }

            if (!v::number()->validate($this->harga)) {
                throw new ValidationException('Harga must be a number');
            }
        } catch (\Exception $e) {
            fm::addMessage([
                'type' => 'error',
                'title' => 'Validation Failed',
                'message' => $e->getMessage(),
                'context' => 'tambah_paket_validation_error'
            ]);
            return false;
        }

        return true;
    }
}
