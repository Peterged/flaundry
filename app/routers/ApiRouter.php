<?php

namespace App\routers;

use App\libraries\PHPExpress;

$apiRouter = new PHPExpress();
// initial route = /api
$apiRouter->get('/', function ($req, $res) { // /contact
    $res->render('/pages/index');
});

$apiRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});



$apiRouter->get('/users/robots', function ($req, $res) use ($con) {
    $query = "SELECT * FROM tb_user";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
                $old_key = null;
                if(isset($jsonData['old_key'])) {
                    $old_key = $jsonData['old_key'];
                }
                $key = $jsonData['key'];
                $value = $jsonData['value'];

                if(isset($_SESSION[$old_key]) && $old_key != $key && $old_key) {
                    unset($_SESSION[$old_key]);
                }
                // echo "Key: $key, Value: $value";
                
                $_SESSION[$key] = $value;
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
