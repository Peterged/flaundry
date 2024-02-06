<?php 
    
namespace App\Interfaces;
use App\Models\SaveResult;

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