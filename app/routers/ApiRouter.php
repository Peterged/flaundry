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
