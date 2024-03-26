<?php

namespace App\routers;

use App\Libraries\PHPExpress;

$apiRouter = new PHPExpress();
// initial route = /api
$apiRouter->get('/', function ($req, $res) { // /contact
    $res->render('/pages/index');
});

$apiRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});

$apiRouter->get('/panel/dashboard', function ($req, $res) use ($con) { // /api/panel/dashboard
    $week_start = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $week_end = date('Y-m-d 23:59:59', strtotime('sunday this week'));
    
    // SQL QUERY
    // $queryTest = "SELECT * FROM tb_transaksi WHERE tgl BETWEEN '$week_start' AND '$week_end'";
    $query = "SELECT tb_transaksi.tgl AS tgl_transaksi, tb_detail_transaksi.total_harga AS total_harga FROM tb_transaksi INNER JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi WHERE tb_transaksi.dibayar = 'dibayar' AND tb_transaksi.tgl BETWEEN '$week_start' AND '$week_end' ORDER BY tb_transaksi.tgl";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $dataPendapatan = [];
    $date = "";
    $idx = 0;

    foreach($data as $transaksi) {
        $transaksi['tgl_transaksi'] = date('Y-m-d', strtotime($transaksi['tgl_transaksi']));

        if(!strlen($date) || $date === $transaksi['tgl_transaksi']) {
            if (!isset($dataPendapatan[$idx])) {
                $dataPendapatan[$idx] = 0;
            }
            $dataPendapatan[$idx] += $transaksi['total_harga'];
        }
        else {
            $idx++;
            if (!isset($dataPendapatan[$idx])) {
                $dataPendapatan[$idx] = 0;
            }
            $dataPendapatan[$idx] += $transaksi['total_harga'];
        }
        $date = $transaksi['tgl_transaksi'];
    }

    $total_transaksi = count($data);
    $total_pendapatan = 0;
    foreach ($data as $transaksi) {
        $total_pendapatan += $transaksi['total_harga'];
    }

    header('Content-Type: application/json');
    echo json_encode(['total_transaksi' => $total_transaksi, 'data' => $dataPendapatan], JSON_PRETTY_PRINT);
});

$apiRouter->get('/users/robots', function ($req, $res) use ($con) {
    // $query = "SELECT * FROM tb_user";
    // $stmt = $con->prepare($query);
    // $stmt->execute();
    // $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // $data = [
    //     ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'age' => 28],
    //     ['id' => 2, 'name' => 'Lane Doe', 'email' => 'jane@example.com', 'age' => 25],
    //     ['id' => 3, 'name' => 'Bob Smith', 'email' => 'bob@example.com', 'age' => 32],
    //     ['id' => 4, 'name' => 'Alice Johnson', 'email' => 'alice@example.com', 'age' => 59]
    // ];

    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
});

$apiRouter->post('/session', function ($req, $res) {

    header("Content-Type: application/json");
    function update($data)
    {
        if (is_string($data)) {
            $data = str_replace("'", '"', $data);
            $data = json_decode($data, true);
        }
        $jsonData = $data;

        if ((array)!$jsonData) {
            if (isset($jsonData['key']) && isset($jsonData['value'])) {

                $oldValue = $jsonData['old_value'] ?? '';
                if (json_validate($jsonData['old_value'])) {
                    return;
                }

                $old_key = null;
                if (isset($jsonData['old_key'])) {
                    $old_key = $jsonData['old_key'];
                }
                $key = $jsonData['key'];
                $value = $jsonData['value'];
                if (isset($_SESSION[$old_key]) && $old_key != $key && $old_key) {
                    unset($_SESSION[$old_key]);
                }
                if (strlen($key) == 0) {
                    unset($_SESSION[$old_key]);
                } else {
                    $_SESSION[$key] = $value;
                }
            }
        }

        return $_SESSION;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        @$requestData = file_get_contents('php://input');
        $data = update($requestData);
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        // $requestData = "{'key':'test','value':500}";
        // $data = update($requestData);
        // echo json_encode($data, JSON_PRETTY_PRINT);
        echo json_encode($_SESSION, JSON_PRETTY_PRINT);
    }
});
