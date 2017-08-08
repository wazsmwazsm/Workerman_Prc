<?php
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;


// 创建一个 worker , 监听指定端口, http 协议通信
// UNIX 域套接字可以在同一台主机上各进程间传递描述符
// 实际上创建了一个符号链接 /tmp/my.sock
$http_worker = new Worker("unix:///tmp/my.sock");

// 启动 1 个进程
// $http_worker->count = 1;

// 响应函数
$http_worker->onMessage = function($con, $data) {
    $con->send($data);
};

// 启动 worker
Worker::runAll();
