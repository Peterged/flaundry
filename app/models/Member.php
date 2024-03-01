<?php

namespace App\models;
use App\Libraries\Model;
use App\Attributes\Table;
use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
use App\Services\FlashMessage as fm;



class Member extends Model
{
    public string $id;
    public string $nama;
    public string $alamat;
    public string $jenis_kelamin;
    public string $tlp;

    #[Table('tb_member')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->setRequiredProperties(['nama', 'alamat', 'jenis_kelamin', 'tlp']);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $result = new SaveResult();
        $this->validateSave();

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp) VALUES (:nama, :alamat, :jenis_kelamin, :tlp)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'nama' => $this->nama,
                'alamat' => $this->alamat,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tlp' => $this->tlp
            ]);

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        });

        $result->setSuccess(true);
        return $result;
    }

    private function validateSave()
    {
        try {
            if (!$this->validateEmpty()) {
                throw new ValidationException('All properties are required');
            }

            if (!v::stringType()->min(3)->validate($this->nama)) {
                throw new ValidationException('Nama must contain atleast 3 characters');
            }

            if (!v::stringType()->min(5)->validate($this->alamat)) {
                throw new ValidationException('Alamat must contain atleast 5 characters');
            }

            if (!v::stringType()->in(["L", "P"])->validate($this->jenis_kelamin)) {
                throw new ValidationException('Jenis Kelamin must be a string and either "L" or "P"');
            }

            if (!v::stringType()->min(10)->validate($this->tlp)) {
                throw new ValidationException('Telepon must contain atleast 10 characters');
            }
        } catch (\Exception $e) {
            fm::addMessage([
                'type' => 'error',
                'title' => 'Validation Failed',
                'description' => $e->getMessage(),
                'context' => 'tambah_paket_validation_error'
            ]);
            return false;
        }

        return true;
    }
}
