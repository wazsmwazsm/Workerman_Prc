<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker();
// 开启多少进程则开多少定时任务，注意业务逻辑
$worker->count = 5;
$worker->onWorkerStart = function($worker) {
    // 每 2.5 s 执行一次
    Timer::add(2.5, function() {
        echo "task run\n";
    });
};

Worker::runAll();
