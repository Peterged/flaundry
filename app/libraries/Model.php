<?php

namespace App\libraries;

use App\models\SaveResult;
use App\Attributes\Table;
use Respect\Validation\Rules\Callback;
use Respect\Validation\Validator as v;

interface ModelInterface
{
    public function save(): array | object;
    public function updateOne(array $searchCriteria, array $newData);
    public function updateMany(array | bool $searchCriteria, array $newData);

    public function deleteOne(array $searchCriteria);
    public function deleteMany(array $searchCriteria, array $options = null);
    public function selectOne(array $searchCriteria, array $includedProperties = null);
    public function selectMany(array | bool $searchCriteria, array $includedProperties = null);
}

#[\Attribute]
abstract class Model implements ModelInterface
{
    protected string $tableName;
    protected \PDO $dbConnection;

    protected array $currentRequiredProperties;
    protected array $valuesArray;

    public function __construct(\PDO $PDO, array | null $valuesArray = null, $class = null) {
        if ($class != null) {
            $reflector = new \ReflectionMethod($class, '__construct');
            $attributes = $reflector->getAttributes(Table::class);

            if($tableName = $attributes[0]->newInstance()->tableName) {
                $this->tableName = $tableName;
            }
        }
        $this->dbConnection = $PDO;
        $this->setValuesArray($valuesArray);
    }

    protected function tryCatchWrapper(\Closure $callback, SaveResult &$result = null)
    {
        try {
            $this->dbConnection->beginTransaction();
            $this->dbConnection->exec("LOCK TABLES $this->tableName WRITE");
            $result = $callback();
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            $result->message = $e->getMessage() . " | Line: " . $e->getLine();
            trigger_error($result->message);
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function updateOne(array $searchCriteria, array $newData)
    {
        $tableName = $this->tableName;
        if (isset($newData['password'])) {
            $newData['password'] = password_hash($newData['password'], PASSWORD_DEFAULT);
        }
        $this->tryCatchWrapper(function () use ($searchCriteria, $newData) {
            $query = $this->handleUpdateQuery($searchCriteria, $newData);
            $statement = $this->dbConnection->prepare($query);

            $statement->execute(array_merge($searchCriteria, $newData));
        });
    }

    public function updateMany(array | bool $searchCriteria, array $newData)
    {
        $tableName = $this->tableName;
        if (isset($newData['password'])) {
            $newData['password'] = password_hash($newData['password'], PASSWORD_DEFAULT);
        }

        $this->tryCatchWrapper(function () use ($searchCriteria, $newData, $tableName) {
            $query = "UPDATE $tableName SET ";
            $query .= implode(', ', array_map(function ($value) {
                return "{$value} = :{$value}";
            }, array_keys($newData)));

            $statement = $this->dbConnection->prepare($query);

            $statement->execute(array_merge($searchCriteria, $newData));
        });
        try {
            $this->dbConnection->beginTransaction();
            $this->dbConnection->exec("LOCK TABLES $tableName WRITE");
            if (gettype($searchCriteria) == 'boolean' && $searchCriteria == true) {
                $query = "UPDATE $tableName SET ";
                $query .= implode(', ', array_map(function ($value) {
                    return "{$value} = :{$value}";
                }, array_keys($newData)));
            } else {
                $query = $this->handleUpdateQuery($searchCriteria, $newData);
            }


            $statement = $this->dbConnection->prepare($query);

            $statement->execute(array_merge($searchCriteria, $newData));

            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            echo $e->getMessage();
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function deleteOne(array $searchCriteria)
    {
        $tableName = $this->tableName;
        
        try {
            $this->dbConnection->beginTransaction();
            $this->dbConnection->exec("LOCK TABLES $tableName WRITE");
            $this->handleDeleteQuery($searchCriteria);
            $query = $this->handleDeleteQuery($searchCriteria);
            $query .= " LIMIT 1";

            // $query = 'DELETE FROM tb_user WHERE condition = :condition LIMIT 1'
            $statement = $this->dbConnection->prepare($query);
            $statement->execute($searchCriteria);

            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            trigger_error($e->getMessage());
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    public function deleteMany(array $searchCriteria, array $options = null)
    {
        $tableName = $this->tableName;
        
        try {
            $this->dbConnection->exec("LOCK TABLES $tableName DELETE");
            $this->dbConnection->beginTransaction();
            $query = "DELETE FROM $tableName WHERE ";
            $query .= implode(' AND ', array_map(function ($value) {
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
    public function selectOne(array $searchCriteria, array $includedProperties = null) {
        $tableName = $this->tableName;
        
        try {
            $this->dbConnection->beginTransaction();
            $this->dbConnection->exec("LOCK TABLES $tableName READ");
            $query = $this->handleSelectQuery($searchCriteria, $includedProperties);

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

    // ! selectMany and selectOne are not fixed yet!
    public function selectMany(array | bool $searchCriteria, array $includedProperties = null)
    {
        $tableName = $this->tableName;
        
        try {
            $this->dbConnection->beginTransaction();
            $this->dbConnection->exec("LOCK TABLES $tableName READ");
            if ($searchCriteria == true) {
                $query = "SELECT * FROM $tableName";
            } else {
                $query = $this->handleSelectQuery($searchCriteria, $includedProperties);
            }

            $statement = $this->dbConnection->prepare($query);
            $statement->execute($searchCriteria);

            $this->dbConnection->commit();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            trigger_error($e->getMessage());
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    protected function setRequiredProperties(array $requiredProperties)
    {
        $this->currentRequiredProperties = $requiredProperties;
        $this->checkIfRequiredPropertyValuesAreDefined();
    }

    protected function getRequiredProperties() {
        return $this->currentRequiredProperties ?? null;
    }

    protected function checkIfRequiredPropertiesExistsOnClass() { 
        $requiredProperties = $this->currentRequiredProperties;

        $requiredProperties = array_diff($requiredProperties, $this->valuesArray);
        if (!empty($requiredProperties)) {
            $message = 'Property(s) [' . implode(', ', $requiredProperties) . '] is not defined';
            return [
                'message' => $message,
                'error' => true
            ];
        }
        $error = false;
        return compact('error');
    }

    protected function setValuesArray(array | null $valuesArray) {
        $this->valuesArray = $valuesArray;
    }

    protected function checkIfRequiredPropertyValuesAreDefined() {
        $requiredProperties = $this->currentRequiredProperties;
        foreach ($requiredProperties as $requiredProperty) {
            if (!isset($this->$requiredProperty)) {
                $message = "Property {$requiredProperty} is not defined";
                throw new \Exception($message);
            }
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
        if(!empty($requiredProperties['exclude'])) {
            $requiredProperties = array_diff($requiredProperties, $requiredProperties['exclude']);
        } elseif (!empty($requiredProperties['include'])) {
            $requiredProperties = array_intersect($requiredProperties, $requiredProperties['include']);
        } elseif (!empty($requiredProperties['include']) && !empty($requiredProperties['exclude'])) {
            $requiredProperties = array_diff($requiredProperties['include'], $requiredProperties['exclude']);
        } else {
            $requiredProperties = $this->getTableColumns();
        }
        // Kita mengambil array_keys yang ada di $options, lalu kita mengurangi dengan $includedProperties
        // Hasilnya harusnya kosong, jika tidak kosong, berarti ada property yang tidak diizinkan
        // Didalam funsi __construct() ini, yang harusnya didalam forbiddenProperties adalah
        // ['id']
        $dataKeys = array_keys($data);
        $forbiddenProperties = array_diff($dataKeys, $requiredProperties);

        if (!empty($forbiddenProperties)) {
            $message = 'Property(s) [' . implode(', ', $forbiddenProperties) . '] is not allowed';
            throw new \Exception($message);
        }

        return $requiredProperties;
    }

    protected function handleIncludedAndExcludedKeys(array $requiredProperties)
    {
        if (!empty($requiredProperties['include']) && !empty($requiredProperties['exclude'])) {
            $requiredProperties = array_diff($requiredProperties['include'], $requiredProperties['exclude']);
        } elseif (!empty($requiredProperties['exclude'])) {
            $requiredProperties = array_diff($requiredProperties, $requiredProperties['exclude']);
        } elseif (!empty($requiredProperties['include'])) {
            $requiredProperties = array_intersect($requiredProperties, $requiredProperties['include']);
        } elseif (empty($requiredProperties)) {
            $requiredProperties = $this->getTableColumns();
        }

        return $requiredProperties;
    }
    private function handleUpdateQuery(array &$searchCriteria, array $newData)
    {
        $tableName = $this->tableName;
        try {
            $query = "UPDATE $tableName SET ";
            $query .= implode(', ', array_map(function ($value) {
                return "{$value} = :{$value}";
            }, array_keys($newData)));

            $query .= " WHERE ";
            $query .= implode(' AND ', array_map(function ($value) {
                return "{$value} = :{$value}";
            }, array_keys($searchCriteria)));

        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
        return $query;
    }

    function handleSelectQuery(array | bool $searchCriteria, array $includedProperties = null)
    {
        $tableName = $this->tableName;
        try {
            $includedProperties = $this->handleIncludedAndExcludedKeys($includedProperties);

            if ($searchCriteria == true) {
                $query = "SELECT * FROM $tableName";
            } else {
                $query = "SELECT ";
                if (!empty($includedProperties)) {
                    $query .= implode(', ', array_map(function ($value) {
                        return "$value";
                    }, $includedProperties));
                }

                $query .= " FROM $tableName WHERE ";
                $query .= implode(' AND ', array_map(function ($value) {
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
            $query .= implode(' AND ', array_map(function ($value) {
                return "{$value} = :{$value}";
            }, array_keys($searchCriteria)));

            return $query;
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    public function handleInsertQuery(array $data)
    {
        $tableName = $this->tableName;
        try {
            $query = "INSERT INTO $tableName (";
            $query .= implode(', ', array_map(function ($value) {
                return "$value";
            }, array_keys($data)));

            $query .= ") VALUES (";
            $query .= implode(', ', array_map(function ($value) {
                return ":{$value}";
            }, array_keys($data)));

            $query .= ")";

            return $query;
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    function handleOptionKeys(array $options)
    {
        if (!empty($options['exclude'])) {
            $options = array_diff($options, $options['exclude']);
        } elseif (!empty($options['include'])) {
            $options = array_intersect($options, $options['include']);
        } else {
            $options = $this->getTableColumns();
        }

        return $options;
    }
    public function getTableColumns($inTransaction = false)
    {
        if ($inTransaction) {
            $this->dbConnection->beginTransaction();
        }
        $tableName = $this->tableName;

        try {
            $this->dbConnection->exec("LOCK TABLES $tableName READ");
            $query = "SHOW COLUMNS FROM $tableName";

            $statement = $this->dbConnection->prepare($query);

            $statement->execute();

            $data = $statement->fetchAll(\PDO::FETCH_COLUMN);
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            return $data;
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }
}
