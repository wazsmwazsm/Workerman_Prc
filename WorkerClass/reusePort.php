<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

$worker = new Worker('text://0.0.0.0:600');
$worker->count = 4;

// 每个进程启动后在当前进程新增一个监听
$worker->onWorkerStart = function($worker) {
    // 新建内部进程 (4 个进程，每个都会新建内部进程)
    $inner_worker = new Worker('http://0.0.0.0:2017');
    /**
     * 多个进程监听同一个端口（监听套接字不是继承自父进程）
     * 需要开启端口复用，不然会报Address already in use错误
     */
    $inner_worker->reusePort = TRUE;
    $inner_worker->onMessage = function($con, $data) {
        $con->send("hello ".$con->getRemoteIp().":".$con->getRemotePort()." \n");
    };
    // 执行监听
    $inner_worker->listen();
};

$worker->onMessage = function($con, $data) {
    $con->send("hello \n");
};

// 启动 worker
Worker::runAll();
