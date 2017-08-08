<?php
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;


// 创建一个 worker , 监听指定端口, http 协议通信
$http_worker = new Worker("http://0.0.0.0:600");

// 启动 10 个进程
/*
  进程数设置参考值:

    1、如果业务代码偏向IO密集型，也就是业务代码有IO阻塞的地方，则根据IO密集程度设置进程数，例如CPU核数的3倍。
    2、如果业务代码偏向CPU密集型，也就是业务代码中无IO通讯或者无阻塞式IO通讯，则可以将进程数设置成cpu核数。

*/
// Worker主进程会fork出count个子进程同时监听相同的端口，并行的接收客户端连接，处理连接上的事件。
$http_worker->count = 10;

// 响应函数
$http_worker->onMessage = function($con, $data) {
    $con->send('hello! welcome to workerman!');
};

// 启动 worker
Worker::runAll();
