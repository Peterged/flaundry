<?php
namespace App\utils;

class PrintArray
{
    public static function run($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}

    