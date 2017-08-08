<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

$worker = new Worker('text://0.0.0.0:600');

$worker->onWorkerStart  = function($worker) {
    // 定时，每10秒一次
    Timer::add(10, function() use($worker) {
        // 遍历当前进程所有的客户端连接，发送当前服务器的时间
        foreach ($worker->connections as $connection) {
            // 给每个连接发送当前系统时间
            $connection->send(date("Y-m-d H:i:s", time()));
        }
    });
};

// 启动 worker
Worker::runAll();
