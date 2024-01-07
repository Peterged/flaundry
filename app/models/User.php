<?php

namespace app\models;
class User
{
    private $id;
    private $name;
    private $email;
    private $dbConnection; // Database connection object

    public function __construct($id, $name, $email, $dbConnection)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->dbConnection = $dbConnection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function save()
    {
        // Begin transaction
        $this->dbConnection->beginTransaction();

        try {
            // Lock the user table
            $this->dbConnection->exec('LOCK TABLES users WRITE');

            // Logic to save the user data to the database
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
