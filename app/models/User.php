<?php

namespace App\models;

use App\libraries\Model;
use Respect\Validation\Validator as v;

class User extends Model
{
    private string $id;
    private int $id_outlet;
    private string $nama;
    private string $username;
    private string $password;
    private string $role;


    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        $this->tableName = 'tb_user';
        $this->dbConnection = $PDO;
        if (empty($valuesArray)) {
            return;
        }

        $requiredProperties = ['id_outlet', 'nama', 'username', 'password', 'role'];

        $this->handleForbiddenColumns($valuesArray, $requiredProperties);

        $this->id_outlet = $valuesArray['id_outlet'];
        $this->nama = $valuesArray['nama'];
        $this->username = $valuesArray['username'];
        $this->password = $valuesArray['password'];
        $this->role = $valuesArray['role'];


        $this->username = v::type('string')->notEmpty()->validate($this->username) ? $this->username : null;
        $this->password = v::type('string')->notEmpty()->validate($this->password) ? password_hash($this->password, PASSWORD_DEFAULT) : null;
        $this->role = v::in(['admin', 'kasir', 'owner'])->validate($this->role) ? $this->role : null;
    }

    public function save(): array
    {
        // Begin transaction
        $result = [
            'errorMessage' => null,
            'success' => false,
            'status' => 'commited'
        ];

        $con = $this->dbConnection;
        
        try {
            
            // Lock the user table
            $con->exec("LOCK TABLES {$this->tableName} WRITE");
            $con->beginTransaction();
            $stmt = $this->dbConnection->prepare("
            INSERT INTO {$this->tableName} (id_outlet, nama, username, password, role)
            VALUES (:idOutlet, :nama, :username, :password, :role)
            ");
            
            $stmt->execute([
                'idOutlet' => $this->id_outlet,
                'nama' => $this->nama,
                'username' => $this->username,
                'password' => $this->password,
                'role' => $this->role
            ]);
            echo $con->inTransaction() ? 'true' : 'false';

            
            if($this->username == 'kreshna') {
                throw new \Exception('Nama tidak boleh kreshna');
            }

            $con->commit();
            
            $result['success'] = true;
        } catch (\Exception $e) {
            // Rollback the transaction jika terjadi error
            echo $e->getMessage();
            $con->rollBack();
            $result['errorMessage'] = $e->getMessage();
            $result['status'] = 'rollbacked';
        } finally {
            // Unlock tabelnya supaya dapat diakses kembali seperti biasa
            $con->exec('UNLOCK TABLES');
        }


        return $result;
    }

    

    // Other methods and properties specific to the User model
    public function getIdOutlet()
    {
        return $this->id_outlet;
    }
    public function getNama()
    {
        return $this->nama;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRole()
    {
        return $this->role;
    }

    public function setIdOutlet($id_outlet)
    {
        $this->id_outlet = $id_outlet;
    }
    public function setNama($nama)
    {
        $this->nama = $nama;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }
}
