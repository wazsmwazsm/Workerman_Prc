<?php

use Workerman\Worker;
require_once __DIR__ . '/Workerman/Autoloader.php';

// 创建一个 worker , 监听指定端口, http 协议通信
$ws_worker = new Worker("websocket://0.0.0.0:600");

// 启动 10 个进程
$ws_worker->count = 10;

// 响应函数
$ws_worker->onMessage = function($con, $data) {
    // 向客户端发送数据
    $con->send('hello!'.$data);
};

// 启动 worker
Worker::runAll();
