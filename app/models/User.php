<?php

namespace App\models;
use App\libraries\Model;

class User extends Model
{
    private string $tableName = 'tb_user';
    private string $id;
    private int $id_outlet;
    private string $nama;
    private string $username;
    private string $password;
    private string $role;

    
    public function __construct(array | null $valuesArray)
    {
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
    }

    public function save(): bool | \Exception
    {
        // Begin transaction
        $this->dbConnection->beginTransaction();

        try {
            // Lock the user table
            $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");

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

            // Commit supaya dapat berjalan
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            // Rollback the transaction jika terjadi error
            $this->dbConnection->rollback();
            throw $e;
        } finally {
            // Unlock tabelnya supaya dapat diakses kembali seperti biasa
            $this->dbConnection->exec('UNLOCK TABLES');
        }
        return true;
    }

    public function getUsers(array $keys)
    {
        $this->dbConnection->beginTransaction();

        try {
            $this->dbConnection->exec("LOCK TABLES {$this->tableName} READ");
            $stmt = $this->dbConnection->prepare("SELECT * FROM {$this->tableName}");
            $stmt->execute();

            $this->dbConnection->commit();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            throw $e;
        } finally {
            $this->dbConnection->exec('UNLOCK TABLES');
        }
    }

    public function delete()
    {
        // Begin transaction
        $this->dbConnection->beginTransaction();

        try {
            // Lock the user table
            $this->dbConnection->exec('LOCK TABLES tb_user WRITE');

            $stmt = $this->dbConnection->prepare("DELETE FROM {$this->tableName} WHERE id = :id");

            $stmt->execute([
                'id' => $this->id
            ]);
            
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            // Rollback the transaction jika terjadi error
            $this->dbConnection->rollback();
            throw $e;
        } finally {
            // Unlock tablenya supaya dapat diakses kembali seperti biasa
            $this->dbConnection->exec('UNLOCK TABLES');
        }
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
