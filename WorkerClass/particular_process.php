<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

$worker = new Worker('tcp://0.0.0.0:600');
$worker->count = 4;

$worker->onWorkerStart = function($worker) {
    // 只在 id 编号为 0 的进程上设置定时器
    if($worker->id === 0) {
        Timer::add(1, function() use($worker) {
            echo "4 个worker进程，只在 {$worker->id} 号进程设置定时器\n";
        });
    }
};

// 启动 worker
Worker::runAll();
