<?php
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;


// 创建一个 worker , 监听指定端口, http 协议通信
$http_worker = new Worker("http://0.0.0.0:600");

// 启动 10 个进程
// Worker主进程会fork出count个子进程同时监听相同的端口，并行的接收客户端连接，处理连接上的事件。
$http_worker->count = 10;

// 响应函数
$http_worker->onMessage = function($con, $data) {
    $con->send('hello! welcome to workerman!');
};

// 启动 worker
Worker::runAll();
