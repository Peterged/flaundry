<?php
namespace App\models;
use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;

class Outlet extends Model {
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

    public function save(): array | object {
        $result = new SaveResult();
        $this->validateSave();

        $this->tryCatchWrapper(function() use ($result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO {$this->tableName} (nama, alamat, tlp) VALUES (:nama, :alamat, :tlp)";
            $stmt = $con->prepare($sql);
            $data = $stmt->execute([
                'nama' => $this->nama,
                'alamat' => $this->alamat,
                'tlp' => $this->tlp
            ]);

            $result->setData($data);
            return $result;
        }, $result);

        $result->setSuccess(true);
        return $result;
    }

    private function validateEmpty(): bool {
        $requiredProperties = $this->getRequiredProperties();
        print_r($requiredProperties);
        if(count($requiredProperties) > 0) {
            foreach($requiredProperties as $property) {

                if(empty($this->{$property})) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    private function validateSave() {
        if(!$this->validateEmpty()) {
            throw new ValidationException('All properties are required');
        }

        if(!v::stringType()->min(3)->validate($this->nama)) {
            throw new ValidationException('Nama must contain atleast 3 characters');
        }

        if(!v::stringType()->min(5)->validate($this->alamat)) {
            throw new ValidationException('Alamat must contain atleast 5 characters');
        }

        if(!v::stringType()->min(8)->validate($this->tlp)) {
            throw new ValidationException('Telepon must contain atleast 8 characters');
        }

        return true;
    }
}
