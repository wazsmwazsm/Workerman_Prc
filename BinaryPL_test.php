<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
// 创建一个 worker , 监听指定端口, http 协议通信
$binary_worker = new Worker("BinaryPL://0.0.0.0:600");

// 启动 10 个进程
$binary_worker->count = 1;

// 响应函数
$binary_worker->onMessage = function($con, $data) {

    $save_path = '/home/vagrant/tmp/'.$data['file_name'];
    file_put_contents($save_path, $data['file_data']);
    $con->send("upload success. save path $save_path");
};

// 启动 worker
Worker::runAll();
