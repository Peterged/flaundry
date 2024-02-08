<?php 
namespace App\models;
use App\Libraries\Model;
use App\Attributes\Table;

use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
    
class Member extends Model {
    private string $id;
    private int $nama;
    private string $alamat;
    private string $jenis_kelamin;
    private string $tlp;

    #[Table('tb_member')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->setRequiredProperties(['nama', 'alamat', 'jenis_kelamin', 'tlp']);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object {
        $result = new SaveResult();
        $this->validateSave();

        $this->tryCatchWrapper(function() use ($result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp) VALUES (:nama, :alamat, :jenis_kelamin, :tlp)";
            $stmt = $con->prepare($sql);
            $data = $stmt->execute([
                'nama' => $this->nama,
                'alamat' => $this->alamat,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tlp' => $this->tlp
            ]);

            $result->data = $data;
            return $result;
        }, $result);

        $result->success = true;
        return $result;
    }

    private function validateEmpty(): bool {
        $requiredProperties = $this->getRequiredProperties();

        if(count($requiredProperties) > 0) {
            foreach($requiredProperties as $property) {
                if(empty($this->$property)) {
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

        if(!v::stringType()->in(["L", "P"])->validate($this->jenis_kelamin)) {
            throw new ValidationException('Jenis Kelamin must be a string and either "L" or "P"');
        }

        if(!v::stringType()->min(10)->regex("/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/")->validate($this->tlp)) {
            throw new ValidationException('Telepon must contain atleast 10 characters');
        }

        return true;
    }
}