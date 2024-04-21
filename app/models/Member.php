<?php

namespace App\models;
use App\Libraries\Model;
use App\Attributes\Table;
use Respect\Validation\Validator as v;
use App\Exceptions\ValidationException;
use App\Services\FlashMessage as fm;



class Member extends Model
{
    public string $id;
    public string $nama;
    public string $alamat;
    public string $jenis_kelamin;
    public string $tlp;

    #[Table('tb_member')]
    public function __construct(\PDO $PDO, array | null $valuesArray = null)
    {
        parent::__construct($PDO, $valuesArray, __CLASS__);
        $this->setRequiredProperties(['nama', 'alamat', 'jenis_kelamin', 'tlp']);
        $this->checkIfRequiredPropertiesExistsOnClass();
    }

    public function save(): array | object
    {
        $result = new SaveResult();
        if (!$this->validateSave()) {
            (new \App\Libraries\Response)->redirect($_SERVER['REQUEST_URI']);
        }

        $this->tryCatchWrapper(function () use (&$result) {
            $con = $this->dbConnection;
            $sql = "INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp) VALUES (:nama, :alamat, :jenis_kelamin, :tlp)";
            $stmt = $con->prepare($sql);
            $stmt->execute([
                'nama' => $this->nama,
                'alamat' => $this->alamat,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tlp' => $this->tlp
            ]);

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? [[]];

            $result->setData($data);
        });

        $result->setSuccess(true);
        return $result;
    }

    public function validateSave(array | null $body = null)
    {
        $namaMinLength = 3;
        $namaMaxLength = 36;
        $alamatMinLength = 5;
        $alamatMaxLength = 100;
        $jenisKelaminOptions = ["L", "P"];
        $tlpMinLength = 3;
        $tlpMaxLength = 15;
        try {
            if ($body) {
                $this->nama = $body['nama'];
                $this->alamat = $body['alamat'];
                $this->jenis_kelamin = $body['jenis_kelamin'];
                $this->tlp = $body['tlp'];
            }

            if (!v::stringType()->length($namaMinLength, $namaMaxLength)->validate($this->nama)) {
                throw new ValidationException("Nama harus diantara $namaMinLength dan $namaMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->length($alamatMinLength, $alamatMaxLength)->validate($this->alamat)) {
                throw new ValidationException("Alamat harus diantara $alamatMinLength dan $alamatMaxLength karakter", FLASH_ERROR);
            }

            if (!v::stringType()->in($jenisKelaminOptions)->validate($this->jenis_kelamin)) {
                throw new ValidationException("Jenis Kelamin harus bernilai 'L' or 'P'", FLASH_ERROR);
            }

            if (!v::stringType()->length($tlpMinLength, $tlpMaxLength)->validate($this->tlp)) {
                throw new ValidationException("Telepon harus diantara $tlpMinLength dan $tlpMaxLength karakter", FLASH_ERROR);
            }
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                if ($e->getErrorDisplayType() === FLASH_ERROR) {
                    fm::addMessage([
                        'type' => 'error',
                        'title' => 'Validation Failed',
                        'description' => $e->getMessage(),
                        'context' => 'member_message'
                    ]);
                }
            } else {
                fm::addMessage([
                    'type' => 'error',
                    'title' => 'Something went wrong',
                    'description' => "Something went wrong, please try again later",
                    'context' => 'member_message'
                ]);
            }

            return false;
        }

        return true;
    }
}
