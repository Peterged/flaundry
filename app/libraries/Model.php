<?php

namespace App\Libraries;

use App\models\SaveResult;
use App\Attributes\Table;
use Respect\Validation\Validator as v;
use App\Interfaces\ModelInterface;
use App\Utils\MyLodash as _;

#[\Attribute]
final class Model implements ModelInterface
{
    protected string $tableName;
    protected \PDO $dbConnection;

    protected array $currentRequiredProperties;
    protected array $valuesArray = [];
    protected array $tempValueArray = [];

    public function __construct(\PDO $PDO, array | null $valuesArray = null, $class = null)
    {
        if ($class != null) {
            $reflector = new \ReflectionMethod($class, '__construct');
            $attributes = $reflector->getAttributes(Table::class);

            if ($tableName = $attributes[0]->newInstance()->tableName) {
                $this->tableName = $tableName;
            }
            
        }
        $this->dbConnection = $PDO;
        $this->setValuesArray($valuesArray ?? []);
    }

    protected function tryCatchWrapper(\Closure $callback, SaveResult &$result = null)
    {
        $result = new SaveResult();
        try {
            if(isset($this->tableName)) {
                $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");
            }

            $result = $callback() ?? $result;
            if(isset($this->tableName)){
                $this->dbConnection->exec("LOCK TABLES $this->tableName WRITE");
            }
                
            $result = $callback();
            
        } catch (\Exception $e) {
            $result->message = $e->getMessage() . " | Line: " . $e->getLine();
            trigger_error($result->message);
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
    }

    /**
     * Get a user from the database
     * @param array $searchCriteria search criteria to be used in the query
     * @param array | null $valueArray
     * @param bool $noBeginTransaction
     *
     *
     * ```php
     * // Example usage
     * ->get([
     *      'where' => ['nama' => 'kreshna'],
     *      'columns' => 'nama,username'
     * );
     * ```
     */
    public function get(array $searchCriteria = [], string $additionalQuery = "", bool $noBeginTransaction = false)
    {
        if (!$this->validateGetSearchCriteriaArray($searchCriteria)) {
            throw new \App\Exceptions\ValidationException('Search criteria is not valid!');
        }

        $result = new SaveResult();
        try {
            $this->dbConnection->exec("LOCK TABLES {$this->tableName} WRITE");

            $query = $this->convertGetSearchCriteriaIntoQuery($searchCriteria, $additionalQuery);

            $stmt = $this->dbConnection->prepare($query);

            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $result->setSuccess(true);
            $result->setData($data);
        } catch (\Exception $e) {
            
            $result->setMessage($e->getMessage() . " | Line: " . $e->getLine());
            $result->setStatus('rollbacked');
            // trigger_error($e);
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
            
        }


        return $result;
    }

    protected function validateGetSearchCriteriaArray(array &$searchCriteria): bool
    {
        $isAllContentsOfSearchCriteriaArrayAreStringOrArray = _::every($searchCriteria, function ($value) {
            return gettype($value) == 'string' or gettype($value) == 'array';
        });

        $columns = $this->getTableColumns();

        $columnsArray = implode("|", $columns);
        $whereEmptyFlag = '__ALWAYS';

        $whereFnFunction = fn (array $val) => v::arrayType()->notEmpty()->call(
            'array_keys',
            v::each(v::in([...$columns, $whereEmptyFlag]))
        )
            ->each(
                v::oneOf(v::stringType(), v::number(), v::boolType())
            )
            ->validate($val);

        $validKeys = [
            'where?' => $whereFnFunction,
            'whereOr?' => $whereFnFunction,

            'select?' => fn (array | string $val) => v::oneOf(
                v::stringType()->notEmpty()->regex("/^(($columnsArray)+)(\s*[,|]\s*\w+)*$/"),
                v::arrayType()->notEmpty()->call(
                    'array_keys',
                    v::each(v::in($columns))
                )
                    ->each(
                        v::oneOf(v::stringType(), v::number(), v::boolType())
                    )
            )->validate($val)
        ];
        $searchCriteriaKeys = array_keys($searchCriteria);
        $validKeysArray = array_keys($validKeys);

        $isSearchCriteriaKeysValid = _::every($validKeysArray, function ($key, $value, $index) use (
            $validKeys,
            $columnsArray,
            $validKeysArray,
            &$searchCriteria,
            $searchCriteriaKeys,
            $whereEmptyFlag
        ) {
            $searchCriteriaKey = $searchCriteriaKeys[$index] ?? '';

            // _::clog(in_array($searchCriteriaKey, $validKeysArray));
            if (preg_match("/\?$/", $key) && !in_array($searchCriteriaKey, $validKeysArray)) {
                $isValid = false;

                switch ($key) {
                    case 'select?':
                        $isValid = $validKeys[$key]($columnsArray);
                        $searchCriteria['select'] = $columnsArray;
                        break;
                    case 'where?':
                        $searchCriteria['where'] = [$whereEmptyFlag => 1];
                        $isValid = $validKeys[$key]($searchCriteria['where']);
                        break;
                    case 'whereOr?':
                        $searchCriteria['whereOr'] = [$whereEmptyFlag => 1];
                        $isValid = $validKeys[$key]($searchCriteria['whereOr']);
                }

                return $isValid;
            }

            $keyCriteria = preg_match("/\?$/", $key) ? substr($key, 0, -1) : $key;

            return in_array($key, array_keys($validKeys)) && $validKeys[$key]($searchCriteria[$keyCriteria]);
        });

        

        // $isSearchCriteriaKeysValid = true;
        // $isAllContentsOfSearchCriteriaArrayAreStringOrArray = true;

        if (
            empty($searchCriteria)
            || !$isAllContentsOfSearchCriteriaArrayAreStringOrArray
            || !$isSearchCriteriaKeysValid
        ) {

            return false;
        }
        
        $selectCriteria = null;
        
        if(is_array($searchCriteria['select'])) {
            $selectCriteria = _::uniq($searchCriteria['select']);
            
        }
        else {
            $selectCriteria = array_unique(preg_split("/[|,]\s*/", $searchCriteria['select']));
        }
        $searchCriteria['select'] = implode(', ', $selectCriteria);
        return true;
    }

    protected function convertGetSearchCriteriaIntoQuery(array $searchCriteria, string $additionalQuery = ""): string
    {
        $searchCriteria = $this->validateGetSearchCriteriaArray($searchCriteria) ? $searchCriteria : null;
        if ($searchCriteria == null) {
            return '';
        }

        $query = "SELECT ";
        $query .= $searchCriteria['select'];
        $query .= " FROM $this->tableName";

        $whereArray = _::reduce($searchCriteria, function ($result, $value, $key) use($searchCriteria) {
            
            if (!str_starts_with('where', $key)) return $result;
            if (!$searchCriteria[$key] == '__ALWAYS') {
                $result[$key] = $value;
                
                return $result;
            }
        }, []);

        if (!empty($whereArray)) {
            $whereType = '';

            $query .= " WHERE ";

            // print_r($whereArray);            
            foreach ($whereArray as $key => $value) {
                switch ($key) {
                    case 'where':
                        $whereType = 'AND';
                        break;
                    case 'whereOr':
                        $whereType = 'OR';
                        break;
                    default:
                        $whereType = '';
                }
                
                $query .= implode(" $whereType ", array_map(function ($value2, $key2) use ($searchCriteria) {
                    return $key2 . " = " . $value2;
                }, $searchCriteria[$key], array_keys($searchCriteria[$key])));
            }
        }   

        return $query . " $additionalQuery";
    }

    /**
     * @param string $query
     * @param array $params
     * @throws \Exception
     *
     * @description query() is a method to execute a query
     */
    public function query(string $prepareQuery, array $params = null)
    {
        $result = new SaveResult();
        $data = [];
        $this->tryCatchWrapper(function () use ($prepareQuery, $params, &$result, &$data) {
            $statement = $this->dbConnection->prepare($prepareQuery);
            $statement->execute($params ?? []);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        });
        return $result;
        return $data;
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
    public function selectOne(array $searchCriteria, array $includedProperties = null)
    {
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
        // $this->checkIfRequiredPropertyValuesAreDefined();
    }

    protected function getRequiredProperties()
    {
        return $this->currentRequiredProperties ?? null;
    }

    protected function checkIfRequiredPropertiesExistsOnClass()
    {
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

    protected function setValuesArray(array | null $valuesArray)
    {
        try {
            if (array_is_list($valuesArray)) {
                $this->valuesArray = $valuesArray;
                foreach ($valuesArray as $key => $value) {
                    if (isset($this->{$key})) {
                        $this->{$key} = $value;
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }

    protected function setTemporaryValuesArray(array | null $valuesArray)
    {
        try {
            if (array_is_list($valuesArray)) {
                $this->tempValueArray = $valuesArray;
                foreach ($valuesArray as $key => $value) {
                    if (isset($this->{$key})) {
                        $this->{$key} = $value;
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }



    protected function checkIfRequiredPropertyValuesAreDefined()
    {
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
    protected function handleForbiddenColumns(array $data, array $requiredProperties)
    {
        if (!empty($requiredProperties['exclude'])) {
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
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        } finally {
            $this->dbConnection->exec("UNLOCK TABLES");
        }
        return $data;
    }
}
