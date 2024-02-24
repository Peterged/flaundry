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
    public string $id_outlet;
    public string $nama;
    public string $username;
    public string $password;
    public string $role;

    #[Table('tb_member')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->setRequiredProperties(['id_outlet', 'nama', 'username', 'password', 'role']);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $result = new SaveResult();
        $this->validateSave();

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO tb_user (id_outlet, nama, username, password, role) VALUES (:id_outlet, :nama, :username, :password, :role)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'id_outlet' => $this->id_outlet,
                'nama' => $this->nama,
                'username' => $this->username,
                'password' => $this->password,
                'role' => $this->role
            ]);

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        });

        $result->setSuccess(true);
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
                throw new ValidationException('All fields are required');
            }

            if (!v::stringType()->min(3)->validate($this->nama)) {
                throw new ValidationException('Name must be a string and at least 3 characters long');
            }

            if (!v::stringType()->min(3)->validate($this->username)) {
                throw new ValidationException('Username must be a string and at least 3 characters long');
            }

            if (!v::stringType()->min(3)->validate($this->password)) {
                throw new ValidationException('Password must be a string and at least 3 characters long');
            }

            if (!v::stringType()->in(['admin', 'kasir', 'owner'])->validate($this->role)) {
                throw new ValidationException('Role must be a string and either "admin", "kasir", or "owner"');
            }
        } catch (\Exception $e) {
            fm::addMessage([
                'type' => 'error',
                'title' => 'Validation Failed',
                'description' => $e->getMessage(),
                'context' => 'karywan_message'
            ]);
            return false;
        }

        return true;
    }
}
