<?php

use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

// 创建一个 worker , 监听指定端口, http 协议通信
$tcp_worker = new Worker("tcp://0.0.0.0:600");

// 启动 10 个进程
$tcp_worker->count = 10;

// 响应函数
$tcp_worker->onMessage = function($con, $data) {
    $con->send('hello! '.$data);
};

// 启动 worker
Worker::runAll();
