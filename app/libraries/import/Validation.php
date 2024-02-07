<?php 
    
namespace App\libraries;

class Validation {
    public static function validateEmpty($requiredProperties, $object) {
        if(count($requiredProperties) > 0) {
            foreach($requiredProperties as $property) {
                if(empty($object->$property)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function validateSave($object) {
        if(!self::validateEmpty($object->getRequiredProperties(), $object)) {
            throw new \App\Exceptions\ValidationException('All fields are required');
        }
    }

    public static function validateUpdate($object) {
        if(!self::validateEmpty($object->getRequiredProperties(), $object)) {
            throw new \App\Exceptions\ValidationException('All fields are required');
        }
    }

    public static function 
}