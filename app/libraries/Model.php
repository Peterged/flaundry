<?php

namespace App\libraries;
use Respect\Validation\Validator as v;

interface ModelInterface  {
    public function save(): bool | \Exception;
    public function updateOne(array $searchCriteria, array $newData);
    public function updateMany(array | bool $searchCriteria, array $newData);

    public function deleteOne(array $searchCriteria);
    public function deleteMany(array $searchCriteria);
    public function selectOne(array $searchCriteria);
    public function selectAll(array | bool $searchCriteria);
}

abstract class Model implements ModelInterface
{
    protected string $tableName;
    protected \PDO $dbConnection;

    public function __construct(\PDO $PDO)
    {
        $this->dbConnection = $PDO;
    }

    public function updateOne(array $searchCriteria, array $newData)
    {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;
        try {
            $query = $this->handleUpdateQuery($searchCriteria, $newData) . " LIMIT 1";

            $statement = $this->dbConnection->prepare($query);

            $statement->execute(array_merge($searchCriteria, $newData));

            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function updateMany(array | bool $searchCriteria, array $newData)
    {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;

        try {
            $this->dbConnection->exec("LOCK TABLES $tableName WRITE");
            if(gettype($searchCriteria) == 'boolean' && $searchCriteria == true) {
                $query = "UPDATE $tableName SET ";
                $query .= implode(', ', array_map(function ($value) {
                    return "{$value} = :{$value}";
                }, array_keys($newData)));
            } else {
                $query = $this->handleUpdateQuery($searchCriteria, $newData);
            }
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function deleteOne(array $searchCriteria)
    {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;

        try {
            $query = $this->handleDeleteQuery($searchCriteria);

            $query .= " LIMIT 1";

            $statement = $this->dbConnection->prepare($query);

            $statement->execute($searchCriteria);

            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function deleteMany(array $searchCriteria)
    {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;

        try {
            $this->dbConnection->exec("LOCK TABLES $tableName DELETE");
            $query = "DELETE FROM $tableName WHERE ";
            $query .= implode(' AND ', array_map(function($value) {
                return "{$value} = :{$value}";
            }, array_keys($searchCriteria)));

            $statement = $this->dbConnection->prepare($query);

            $statement->execute($searchCriteria);

            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function selectOne(array $searchCriteria) {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;

        try {
            $this->dbConnection->exec("LOCK TABLES $tableName READ");
            $query = $this->handleSelectQuery($searchCriteria);

            $query .= " LIMIT 1";

            $statement = $this->dbConnection->prepare($query);

            $statement->execute($searchCriteria);

            $this->dbConnection->commit();
            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function selectAll(array | bool $searchCriteria) {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;

        try {
            $this->dbConnection->exec("LOCK TABLES $tableName READ");
            if(gettype($searchCriteria) == 'boolean' && $searchCriteria == true) {
                $query = "SELECT * FROM $tableName";
            } else {
                $query = "SELECT * FROM $tableName WHERE ";
                $query .= implode(' AND ', array_map(function($value) {
                    return "{$value} = :{$value}";
                }, array_keys($searchCriteria)));
            }

            $statement = $this->dbConnection->prepare($query);

            $statement->execute($searchCriteria);

            $this->dbConnection->commit();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    // Additional utility methods
    /**
     * @param array $data
     * @param array $requiredProperties
     * @throws \Exception
     * @description handleRequiredColumns() is a method to handle forbidden columns
     */
    protected function handleForbiddenColumns(array $data, array $requiredProperties) {

        $requiredProperties = ['id_outlet', 'nama', 'username', 'password', 'role'];
        // Kita mengambil array_keys yang ada di $options, lalu kita mengurangi dengan $includedProperties
        // Hasilnya harusnya kosong, jika tidak kosong, berarti ada property yang tidak diizinkan
        // Didalam funsi __construct() ini, yang harusnya didalam forbiddenProperties adalah
        // ['id']
        $dataKeys = array_keys($data);
        $forbiddenProperties = array_diff($dataKeys, $requiredProperties);

        $isForbiddenPropertyKeysInOptions = array_diff($dataKeys, $forbiddenProperties);

        if(!empty($isForbiddenPropertyKeysInOptions)) {
            $message = 'Property(s) [' . implode(', ', $isForbiddenPropertyKeysInOptions) . '] is not allowed';
            throw new \Exception($message);
        }
    }
    private function handleUpdateQuery(array $searchCriteria, array $newData)
    {
        $tableName = $this->tableName;
        try {
            $query = "UPDATE $tableName SET ";
            $query .= implode(', ', array_map(function ($value) {
                return "{$value} = :{$value}";
            }, array_keys($newData)));

            $query .= " WHERE ";
            $query .= implode(' AND ', array_map(function($value) {
                return "{$value} = :{$value}";
            }, array_keys($searchCriteria)));

            return $query;
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    function handleSelectQuery(array | bool $searchCriteria)
    {
        $tableName = $this->tableName;
        try {
            if(gettype($searchCriteria) == 'boolean' && $searchCriteria == true) {
                $query = "SELECT * FROM $tableName";
            } else {
                $query = "SELECT * FROM $tableName WHERE ";
                $query .= implode(' AND ', array_map(function($value) {
                    return "{$value} = :{$value}";
                }, array_keys($searchCriteria)));
            }
            return $query;
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    public function handleDeleteQuery(array $searchCriteria)
    {
        $tableName = $this->tableName;
        try {
            $query = "DELETE FROM $tableName WHERE ";
            $query .= implode(' AND ', array_map(function($value) {
                return "{$value} = :{$value}";
            }, array_keys($searchCriteria)));

            return $query;
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
    }
    public function getTableColumns() {
        $this->dbConnection->beginTransaction();
        $tableName = $this->tableName;

        try {
            $this->dbConnection->exec("LOCK TABLES $tableName READ");
            $query = "SHOW COLUMNS FROM $tableName";

            $statement = $this->dbConnection->prepare($query);

            $statement->execute();

            $this->dbConnection->commit();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }


}