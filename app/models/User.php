<?php

namespace App\models;

class User
{
    private string $id;
    private int $id_outlet;
    private string $nama;
    private string $username;
    private string $password;
    private string $role;
    private \PDO $dbConnection; // Database connection object

    public function __construct(array $valuesArray)
    {
        $requiredProperties = ['id_outlet', 'nama', 'username', 'password', 'role'];
        // Kita mengambil array_keys yang ada di $options, lalu kita mengurangi dengan $includedProperties
        // Hasilnya harusnya kosong, jika tidak kosong, berarti ada property yang tidak diizinkan
        // Didalam funsi __construct() ini, yang harusnya didalam forbiddenProperties adalah
        // ['id']
        $valuesArrayKeys = array_keys($valuesArray);
        $forbiddenProperties = array_diff($valuesArrayKeys, $requiredProperties);

        $isForbiddenPropertiesKeysInOptions = array_diff($valuesArrayKeys, $forbiddenProperties);

        foreach ($isForbiddenPropertiesKeysInOptions as $forbiddenProperty) {
            $message = 'Property(s) [' . implode(', ', $isForbiddenPropertiesKeysInOptions) . '] is not allowed';
            throw new \Exception($message);
        }

        $this->id_outlet = $valuesArray['id_outlet'];
        $this->nama = $valuesArray['nama'];
        $this->username = $valuesArray['username'];
        $this->password = $valuesArray['password'];
        $this->role = $valuesArray['role'];
    }

    public function getIdOutlet() { return $this->id_outlet; }
    public function getNama() { return $this->nama; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }

    public function setIdOutlet($id_outlet) { $this->id_outlet = $id_outlet; }
    public function setNama($nama) { $this->nama = $nama; }
    public function setUsername($username) { $this->username = $username; }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = $role; }

    public function save()
    {
        // Begin transaction
        $this->dbConnection->beginTransaction();

        try {
            // Lock the user table
            $this->dbConnection->exec('LOCK TABLES tb_user WRITE');

            // Logic to save the user data to the database
            // ...
            mysqli_query($connection, "
            INSERT INTO users (id_outlet, nama, username, password, role)
            VALUES ({$this->id_outlet}, {$this->nama}, {$this->username}, {$this->password}, {$this->role})
            ");

            // Commit the transaction
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            $this->dbConnection->rollback();
            throw $e;
        } finally {
            // Unlock the user table
            $this->dbConnection->exec('UNLOCK TABLES');
        }
    }



    public function delete()
    {
        // Begin transaction
        $this->dbConnection->beginTransaction();

        try {
            // Lock the user table
            $this->dbConnection->exec('LOCK TABLES users WRITE');

            // Logic to delete the user from the database
            // ...

            // Commit the transaction
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            $this->dbConnection->rollback();
            throw $e;
        } finally {
            // Unlock the user table
            $this->dbConnection->exec('UNLOCK TABLES');
        }
    }

    // Other methods and properties specific to the User model

}

$connection = new \PDO('mysql:host=localhost;dbname=example', 'root', '');
$user = new User('1', 'John Doe', '', $connection);
$user->save();
