<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker();
$worker->count = 1;
$worker->onWorkerStart = function($worker) {
    $timer_id = Timer::add(2, function() {
        echo "task run\n";
    });

    // 设置一个一次性任务 20s 后关闭定时器
    Timer::add(20, function($timer_id) {
        echo "Timer::del($timer_id)\n";
        Timer::del($timer_id);
    }, [$timer_id], FALSE);
};

Worker::runAll();
