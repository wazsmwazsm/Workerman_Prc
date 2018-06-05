<?php
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Protocols\Http;

$http_worker = new Worker("http://0.0.0.0:600");

$http_worker->count = 4;

// 响应函数
$http_worker->onMessage = function($con, $data) {
    Http::header("Location: https://www.baidu.com/");
    $con->send('hello! welcome to workerman!');
};

// 启动 worker
Worker::runAll();
