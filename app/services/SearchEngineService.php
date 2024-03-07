<?php 
    
namespace App\Services;
use App\Utils\MyLodash as _;

class SearchEngineService
{
    public function __construct()
    {
    }

    public static function filterSearch(string | array $search): string | array {
        $searchArray = is_array($search) ? $search : [$search];
        foreach($searchArray as $key => &$value) {
            $value = trim($value);
            $value = htmlspecialchars($value);
            $searchArray[$key] = $value;
        }
        if(is_string($search)) {
            return $searchArray[0] ?? '';
        }
        return $searchArray;
    }
}