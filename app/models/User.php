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
        parent::__construct($PDO, $valuesArray);

        // Set the required properties 
        $this->setRequiredProperties(['username', 'password']);
        $this->tableName = 'tb_user';

        // Compare 2 arrays, if empty, the properties are set, if not empty
        // then throw an exception
        $this->checkIfRequiredPropertiesExistsOnClass();

        foreach($valuesArray as $key => $value) {
            $this->{$key} = $value;
        }

    }


    public static function logout()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function save(): array
    {
        $this->setRequiredProperties(['id_outlet', 'nama', 'username', 'password', 'role']);

        $this->role = v::in(['admin', 'kasir', 'owner'])->validate($this->role) ? $this->role : null;

        $result = [
            'errorMessage' => null,
            'success' => false,
            'status' => 'commited'
        ];


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
            echo $con->inTransaction() ? 'true' : 'false';

            
            if($this->username == 'kreshna') {
                throw new \Exception('Nama tidak boleh kreshna');
            }

            if ($this->username == 'kreshna') {
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
    public function login(): array {
        

        $con = $this->dbConnection;

        try {
            $con->exec("LOCK TABLES {$this->tableName} WRITE");
            $con->beginTransaction();

            $stmt = $con->prepare("
            SELECT * FROM {$this->tableName} WHERE username = :username
            ");

            $stmt->execute([
                'username' => $this->username
            ]);

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$user) {
                $_SESSION['displayMessage'] = 'User not found!';
                throw new \Exception('User not found!');
            }

            if (!password_verify($this->password, $user['password'])) {
                $_SESSION['displayMessage'] = 'Username / Password salah!';
                throw new \Exception('Username / Password salah!');
            }

            $con->commit();

            $result['success'] = true;
            $result['user'] = $user;
        } catch (\Exception $e) {
            $con->rollBack();
            $result['errorMessage'] = $e->getMessage() . " | Line: " . $e->getLine();
            $result['status'] = 'rollbacked';
        } finally {
            $con->exec('UNLOCK TABLES');
        }

        return $result;
    }

    public function register(): array {
        $result = [
            'errorMessage' => null,
            'success' => false,
            'status' => 'commited'
        ];

        try {
            $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");

            $this->dbConnection->beginTransaction();

            $columnsToBeInsertedTo = implode(", ", $this->getRequiredProperties());
            $columnsPrepareValues = implode(", ", array_map(function($prop) {
                return ":$prop";
            }, $this->getRequiredProperties()));

            $stmt = $this->dbConnection->prepare("INSERT INTO {$this->tableName} ($columnsToBeInsertedTo) VALUES ($columnsPrepareValues)");

            $stmt->execute($this->valuesArray);

            $this->dbConnection->commit();
            $result['success'] = true;
        }
        catch(\Exception $e) {
            $result['errorMessage'] = $e->getMessage();
            $result['status'] = 'rollbacked';
            $this->dbConnection->rollBack();
            trigger_error($e->getMessage() . " | Line: " . $e->getLine());
        }
        finally {
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
