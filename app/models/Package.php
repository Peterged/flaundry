<?php

namespace App\models;

use App\Libraries\Model;
use Respect\Validation\Validator as v;


class Package extends Model {
    private int $id;
    private int $id_outlet;
    private string $jenis;
    private string $nama_paket;
    private int $harga;

    private array $requiredProperties = ['id_outlet', 'jenis', 'nama_paket', 'harga'];

    public function __construct(\PDO $PDO, array | null $valuesArray = null) {
        $this->tableName = 'tb_user';
        $this->dbConnection = $PDO;
        if (empty($valuesArray)) {
            return;
        }

        
    }

    public function save() {
        $con = $this->dbConnection;
        $stmt = $con->prepare("
            INSERT INTO {$this->tableName} (id_outlet, jenis, nama_paket, harga)
            VALUES (:idOutlet, :jenis, :nama_paket, :harga)
        ");

        $this->tryCatchWrapper(function() use ($stmt) {
            $stmt->bindParam(':idOutlet', $this->id_outlet);
            $stmt->bindParam(':jenis', $this->jenis);
            $stmt->bindParam(':nama_paket', $this->nama_paket);
            $stmt->bindParam(':harga', $this->harga);
        });

        return $con;
    }
}