<?php

namespace App\models;

use App\libraries\Model;
use Respect\Validation\Validator as v;


class Package extends Model {
    private int $id;
    private int $id_outlet;
    private string $jenis;
    private string $nama_paket;
    private int $harga;

    public function __construct(\PDO $PDO, array | null $valuesArray = null) {
        $this->tableName = 'tb_user';
        $this->dbConnection = $PDO;
        if (empty($valuesArray)) {
            return;
        }

        
    }
}