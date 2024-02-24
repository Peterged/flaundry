<?php

namespace App\models;

use App\Libraries\Model;
use Respect\Validation\Validator as v;
use App\Attributes\Table;
use App\Exceptions\AuthException;
use App\Exceptions\ModelException;
use App\libaries\Essentials\Session;
use App\Services\FlashMessage;
use App\Utils\MyLodash as _;

class User extends Model
{
    public string $id;
    public int $id_outlet;
    public string $nama;
    public string $username;
    public string $password;
    public string $role;


    #[Table('tb_user')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        parent::__construct($PDO, $valuesArray, __CLASS__);

        // Set the required properties
        $this->setRequiredProperties(['username', 'password']);

        // Compare 2 arrays, if empty, the properties are set, if not empty
        // then throw an exception
        $this->checkIfRequiredPropertiesExistsOnClass();

        if ($valuesArray != null)
            foreach ($valuesArray as $key => $value) {
                $this->{$key} = $value;
            }
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['username']);
    }

    public static function logout()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function save(): object
    {
        $this->setRequiredProperties(['id_outlet', 'nama', 'username', 'password', 'role']);

        $this->role = v::in(['admin', 'kasir', 'owner'])->validate($this->role) ? $this->role : null;

        $result = new SaveResult();

        $con = $this->dbConnection;

        try {
            // Lock the user table
            $con->exec("LOCK TABLES {$this->tableName} WRITE");

            $stmt = $this->dbConnection->prepare("
            INSERT INTO {$this->tableName} (id_outlet, nama, username, password, role)
            VALUES (:id_outlet, :nama, :username, :password, :role)
            ");

            $stmt->execute([
                'id_outlet' => $this->id_outlet,
                'nama' => $this->nama,
                'username' => $this->username,
                'password' => $this->password,
                'role' => $this->role
            ]);
            // echo $con->inTransaction() ? 'true' : 'false';

            $con->commit();

            $result->setSuccess(true);
        } catch (ModelException $e) {
            // Rollback the transaction jika terjadi error
            echo $e->getMessage();
            $con->rollBack();

            $result->setMessage($e->getMessage());
            $result->setStatus($e->getStatus());
        } finally {
            // Unlock tabelnya supaya dapat diakses kembali seperti biasa
            $con->exec('UNLOCK TABLES');
        }

        return $result;
    }
    public function login(): object
    {

        $con = $this->dbConnection;
        $result = new SaveResult();
        try {
            $con->exec("LOCK TABLES {$this->tableName} WRITE");

            $stmt = $con->prepare("
            SELECT * FROM {$this->tableName} WHERE username = :username
            ");

            $stmt->execute([
                'username' => $this->username
            ]);

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);


            if (!$user) {
                FlashMessage::addMessage([
                    'type' => 'error',
                    'context' => 'login',
                    'title' => 'Validation Error!',
                    'description' => "User {$this->username} tidak ditemukan!"
                ]);

                throw new AuthException('User not found!');
            }

            if (!password_verify($this->password, $user['password'])) {

                FlashMessage::addMessage([
                    'type' => 'error',
                    'context' => 'login',
                    'title' => 'Validation Error!',
                    'description' => 'Username / Password salah!'
                ]);

                throw new AuthException('Username / Password salah!');
            }

            $con->commit();
            $result->setSuccess(true);
            $result->setData($user);

            FlashMessage::addMessage([
                'type' => 'success',
                'context' => 'welcome-message',
                'title' => 'Congratulations!',
                'description' => 'Login berhasil!'
            ]);

            $_SESSION['username'] = $result->getData()['username'];
            $_SESSION['role'] = $result->getData()['role'];
            $_SESSION['id_outlet'] = $result->getData()['id_outlet'];
            $_SESSION['id_user'] = $result->getData()['id'];
        } catch (\Exception $e) {
            $result->setMessage($e->getMessage() . " | Line: " . $e->getLine());
            $result->setStatus('rollbacked');
        } finally {
            $con->exec('UNLOCK TABLES');
        }

        return $result;
    }

    public function register(): object
    {
        $result = new SaveResult();

        try {
            // This will create a whole new process of getting the user

            if (empty($existingUser) && $existingUser != null) {
                FlashMessage::addMessage([
                    'type' => 'warning',
                    'context' => 'register',
                    'title' => 'Validation Error!',
                    'description' => 'Username sudah ada!'
                ]);

                throw new AuthException('User sudah ada!');
            }


            $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");

            $this->dbConnection->beginTransaction();

            $columnsToBeInsertedTo = implode(", ", $this->getRequiredProperties());
            $columnsPrepareValues = implode(", ", array_map(function ($prop) {
                return ":$prop";
            }, $this->getRequiredProperties()));

            $stmt = $this->dbConnection->prepare("INSERT INTO {$this->tableName} ($columnsToBeInsertedTo) VALUES ($columnsPrepareValues)");

            $stmt->execute($this->valuesArray);

            //

            if (!$user) {
                FlashMessage::addMessage([
                    'type' => 'error',
                    'context' => 'login',
                    'title' => 'Validation Error!',
                    'description' => 'User tidak ditemukan!'
                ]);

                throw new AuthException('User not found!');
            }

            $this->dbConnection->commit();
            $result->setSuccess(true);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            $result->setMessage($e->getMessage() . " | Line: " . $e->getLine());
            $result->setStatus('rollbacked');
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }

        return $result;
    }

    private function checkIfTableIsLocked(): bool
    {
        if ($this->dbConnection->inTransaction()) {
            return true;
        }
        $stmt = $this->dbConnection->prepare("SHOW OPEN TABLES WHERE In_use > 0");
        $stmt->execute();

        $tables = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($tables as $table) {
            if ($table['Table'] == $this->tableName) {
                return true;
            }
        }

        return false;
    }



    public function update(): object
    {
        $result = new SaveResult();

        try {


            $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");

            $this->dbConnection->beginTransaction();

            $columnsToBeInsertedTo = implode(", ", $this->getRequiredProperties());
            $columnsPrepareValues = implode(", ", array_map(function ($prop) {
                return ":$prop";
            }, $this->getRequiredProperties()));

            $stmt = $this->dbConnection->prepare("INSERT INTO {$this->tableName} ($columnsToBeInsertedTo) VALUES ($columnsPrepareValues)");

            $stmt->execute($this->valuesArray);

            if (!$user) {
                FlashMessage::addMessage([
                    'type' => 'error',
                    'context' => 'login',
                    'title' => 'Validation Error!',
                    'description' => 'User tidak ditemukan!'
                ]);

                throw new AuthException('User not found!');
            }

            $this->dbConnection->commit();
            $result->setSuccess(true);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            $result->setMessage($e->getMessage() . " | Line: " . $e->getLine());
            $result->setStatus('rollbacked');
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
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
