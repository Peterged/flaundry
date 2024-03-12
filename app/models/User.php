<?php

namespace App\models;

use App\Libraries\Model;
use Respect\Validation\Validator as v;
use App\Attributes\Table;
use App\Exceptions\AuthException;
use App\Exceptions\ModelException;
use App\Services\FlashMessage;
use App\Utils\MyLodash as _;
use App\Exceptions\ValidationException;

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
        $this->setRequiredProperties(['id_outlet', 'nama', 'username', 'password', 'role']);

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
        $this->validateSave();
        $this->role = v::in(['admin', 'kasir', 'owner'])->validate($this->role) ? $this->role : null;

        $result = new SaveResult();

        $con = $this->dbConnection;

        try {
            // Lock the user table
            $con->exec("LOCK TABLES {$this->tableName} WRITE");
            $query = "
            INSERT INTO {$this->tableName} (id_outlet, nama, username, password, role)
            VALUES (:id_outlet, :nama, :username, :password, :role)
            ";
            $stmt = $this->dbConnection->prepare($query);
            $dataToExecute = [
                'id_outlet' => $this->id_outlet,
                'nama' => $this->nama,
                'username' => $this->username,
                'password' => password_hash($this->password, PASSWORD_DEFAULT),
                'role' => $this->role
            ];


            $stmt->execute($dataToExecute);
            $con->commit();

            $result->setSuccess(true);
        } catch (\Exception $e) {
            // Rollback the transaction jika terjadi error
            $con->rollBack();

            $result->setMessage($e->getMessage());
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

            if (empty($existingUser)) {
                FlashMessage::addMessage([
                    'type' => 'warning',
                    'context' => 'register',
                    'title' => 'Validation Error!',
                    'description' => 'Username sudah ada!'
                ]);

                throw new AuthException('User sudah ada!');
            }


            $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");

            // $this->dbConnection->beginTransaction();

            $columnsToBeInsertedTo = implode(", ", $this->getRequiredProperties());
            $columnsPrepareValues = implode(", ", array_map(function ($prop) {
                return ":$prop";
            }, $this->getRequiredProperties()));
            $query = "INSERT INTO {$this->tableName} ($columnsToBeInsertedTo) VALUES ($columnsPrepareValues)";
            echo $query;
            $stmt = $this->dbConnection->prepare($query);

            $user = $stmt->execute($this->valuesArray);


            if (!$user) {
                FlashMessage::addMessage([
                    'type' => 'error',
                    'context' => 'login',
                    'title' => 'Validation Error!',
                    'description' => 'User tidak ditemukan!'
                ]);

                throw new AuthException('User not found!');
            }

            // $this->dbConnection->commit();
            $result->setSuccess(true);
        } catch (\Exception $e) {
            // $this->dbConnection->rollBack();
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

    public function validateSave(array | null $body = null)
    {
        $namaMinLength = 3;
        $namaMaxLength = 36;
        $usernameMinLength = 3;
        $usernameMaxLength = 24;
        $passwordMinLength = 6;
        $passwordMaxLength = 36;

        try {

            if ($body) {
                $this->id_outlet = $body['id_outlet'];
                $this->nama = $body['nama'];
                $this->username = $body['username'];
                $this->password = $body['password'];
                $this->role = $body['role'];
            }

            if (!v::intVal()->validate($this->id_outlet)) {
                throw new ValidationException('ID Outlet harus dalam bentuk angka!', FLASH_ERROR);
            }

            if (!v::intVal()->min(0)->validate($this->id_outlet)) {
                throw new ValidationException('ID Outlet memiliki nilai tidak valid!', FLASH_ERROR);
            }

            if (!v::stringType()->length($passwordMinLength, $passwordMaxLength)->validate($this->password)) {
                throw new ValidationException("Password harus minimal <b>$passwordMinLength</b> dan maksimal $passwordMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->length($namaMinLength, $namaMaxLength)->validate($this->nama)) {
                throw new ValidationException("Nama harus minimal <b>$namaMinLength</b> dan maksimal $namaMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->length($usernameMinLength, $usernameMaxLength)->validate($this->username)) {
                throw new ValidationException("Username harus minimal <b>$usernameMinLength</b> dan maksimal <b>$usernameMaxLength</b> karakter", FLASH_ERROR);
            }

            if (!v::stringType()->in(['admin', 'kasir', 'owner'])->validate($this->role)) {
                throw new ValidationException('Role harus diantara "admin", "kasir", atau "owner"', FLASH_ERROR);
            }
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    FlashMessage::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => 'karyawan_message'
                    ]);
                }
            } else {
                FlashMessage::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => 'karyawan_message'
                ]);
            }

            return false;
        }

        return true;
    }

    public function validateUpdate(array | null $body = null)
    {
        $namaMinLength = 3;
        $namaMaxLength = 36;
        $usernameMinLength = 3;
        $usernameMaxLength = 24;

        try {
            if ($body) {
                $this->id_outlet = $body['id_outlet'];
                $this->nama = $body['nama'];
                $this->username = $body['username'];
                $this->password = $body['password'];
                $this->role = $body['role'];
            }

            if (!v::intVal()->validate($this->id_outlet)) {
                throw new ValidationException('ID Outlet harus dalam bentuk angka!', FLASH_ERROR);
            }

            if (!v::intVal()->min(0)->validate($this->id_outlet)) {
                throw new ValidationException('ID Outlet memiliki nilai tidak valid!', FLASH_ERROR);
            }

            if (!v::stringType()->length($namaMinLength, $namaMaxLength)->validate($this->nama)) {
                throw new \Exception("Nama harus minimal <b>$namaMinLength</b> dan maksimal $namaMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->length($usernameMinLength, $usernameMaxLength)->validate($this->username)) {
                throw new ValidationException("Username harus minimal $usernameMinLength dan maksimal $usernameMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->in(['admin', 'kasir', 'owner'])->validate($this->role)) {
                throw new ValidationException('Role harus diantara "admin", "kasir", atau "owner"', FLASH_ERROR);
            }
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    FlashMessage::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => 'karyawan_message'
                    ]);
                }
            } else {
                FlashMessage::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => 'karyawan_message'
                ]);
            }

            return false;
        }

        return true;
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
