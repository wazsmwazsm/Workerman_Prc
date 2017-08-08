<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;


// 不执行任何监听的Worker容器，用来处理一些定时任务
$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    // 每 2.5 秒执行一次
    $time_interval = 2.5;
    Timer::add($time_interval, function() {
        echo "task run\n";
    });
};

// 启动 worker
Worker::runAll();
