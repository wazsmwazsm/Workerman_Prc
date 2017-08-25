<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
// 创建一个 worker , 监听指定端口, http 协议通信
$tcp_worker = new Worker("tcp://0.0.0.0:600");

// 启动 10 个进程
$tcp_worker->count = 10;

// 响应函数
$tcp_worker->onMessage = function($con, $data) {
    $con->send('hello! '.$data);
};

/*
  Worker::runAll 启动前都是主进程，启动后 workerman 会根据
  设置的 count 和回调进行 fork 子进程出来。回调的执行是在子进程中的
*/

// 启动 worker
Worker::runAll();
