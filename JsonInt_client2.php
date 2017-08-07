<?php

require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;


// 创建一个 worker , 监听指定端口, http 协议通信
$jsonint_worker = new Worker();

$jsonint_worker->onWorkerStart = function($jsonint_worker) {

    $con = new AsyncTcpConnection('JsonInt://127.0.0.1:600');

    $con->onConnect = function($con) {
        $con->send(['name'=>'jack', 'gen'=>'ma']);
    };

    $con->onMessage = function($con, $data) {
        var_dump($data);
    };

    $con->connect();
};

// 启动 worker
Worker::runAll();
