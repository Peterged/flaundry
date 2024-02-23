<?php

namespace App\Utils;

use App\Exceptions\MyLodashException;
define('NO_DUPLICATES', 1);



class MyLodash
{
    const NO_DUPLICATES = 1;

    private static function tryCatch(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    public static function map(array $array, callable $callback)
    {

        $result = static::tryCatch(function () use ($array, $callback) {
            $result = [];
            $index = 0;
            foreach ($array as $key => &$value) {
                array_push($result, $callback($value, $key, $index, $array));
                $index++;
            }
            return $result;
        });
        return $result;
    }

    public static function filter(array $array, callable $callback)
    {
        $result = static::tryCatch(function () use ($array, $callback) {
            $result = [];
            $index = 0;
            foreach ($array as $key => &$value) {
                if ($callback($value, $key, $index, $array)) {
                    $result[] = $value;
                }
                $index++;
            }
            return $result;
        });
        return $result;
    }

    public static function uniq(array $array)
    {
        $result = static::tryCatch(function () use ($array) {
            return array_unique($array);
        });
        return $result;
    }

    public static function reduce(array $array, callable $callback, $initial = null)
    {
        $result = static::tryCatch(function () use ($array, $callback, $initial) {
            $res = $initial;
            foreach ($array as $key => $value) {
                $res = $callback($res, $value, $key, $array);
            }
            return $res;
        });
        return $result;
    }

    public static function find(array $array, callable $callback)
    {
        $result = static::tryCatch(function () use ($array, $callback) {
            $index = 0;
            foreach ($array as $key => $value) {
                if ($callback($value, $key, $index, $array)) {
                    return $value;
                }
                $index++;
            }
            return null;
        });
        return $result;
    }

    public static function findIndex(array $array, callable $callback)
    {
        $result = static::tryCatch(function () use ($array, $callback) {
            $index = 0;
            foreach ($array as $key => $value) {
                if ($callback($value, $key, $index, $array)) {
                    return $key;
                }
                $index++;
            }
            return -1;
        });
        return $result;
    }

    public static function every(array $array, callable $callback)
    {
        $result = static::tryCatch(function () use ($array, $callback) {
            
            $index = 0;
            
            foreach ($array as $key => &$value) {
                if (!$callback($value, $key, $index, $array)) {
                    return false;
                }
                $index++;
            }
            return true;
        });
        return (bool) $result;
    }

    public static function some(array $array, callable $callback)
    {
        $result = static::tryCatch(function () use ($array, $callback) {
            $index = 0;
            foreach ($array as $key => $value) {
                if ($callback($value, $key, $index, $array)) {
                    return true;
                }
                $index++;
            }
            return false;
        });
        return $result;
    }

    public static function includes($array, $value)
    {
        return in_array($value, $array);
    }

    public static function invokeMap($array, $methodName, $arguments = [])
    {
        $result = static::tryCatch(function () use ($array, $methodName, $arguments) {
            $result = [];
            foreach ($array as $key => $value) {
                $result[] = $value->$methodName(...$arguments);
            }
            return $result;
        });
        return $result;
    }

    public static function keyBy($array, $callback)
    {
        $result = static::tryCatch(function () use ($array, $callback) {
            $result = [];
            $index = 0;
            foreach ($array as $key => $value) {
                $result[$callback($value, $key, $index, $array)] = $value;
                $index++;
            }
            return $result;
        });
        return $result;
    }

    public static function concat(int | float | string | array | bool ...$items)
    {
        $result = static::tryCatch(function () use ($items) {
            $res = [];
            foreach ($items as $item) {
                array_push($res, $item);
            }
            return $res;
        });
        return $result;
    }

    private static function removeDuplicates(array $array)
    {
        return array_map("unserialize", array_unique(array_map("serialize", $array)));
    }

    /**
     * Turns a list of values into a neat array. Printed straight using echo
     */
    public static function clog(...$array)
    {
        $result = "[";

        $count = count($array);
        $i = 0;
        foreach ($array as $array_item) {
            if (is_array($array_item)) {
                $result .= static::clog(...$array_item);
            } else if (is_bool($array_item)) {
                $result .= $array_item ? 'true' : 'false';
            } elseif (is_object($array_item)) {
                $result .= var_export($array_item, true);
            } else {
                $result .= $array_item;
            }

            if ($i < $count - 1) {
                $result .= ", ";
            }
            $i++;
        }

        $result .= "]";
        return $result;
    }
}
