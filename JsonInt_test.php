<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
// 创建一个 worker , 监听指定端口, http 协议通信
$jsonint_worker = new Worker("JsonInt://0.0.0.0:600");

// 启动 10 个进程
$jsonint_worker->count = 1;

// 响应函数
$jsonint_worker->onMessage = function($con, $data) {

    $con->send($data);
};

// 启动 worker
Worker::runAll();
