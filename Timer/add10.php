<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $count = 1;
    // 使用当前 tmer_id ，需要引用传值
    $timer_id = Timer::add(1, function() use(&$timer_id, &$count) {
        echo "Timer run $count\n";
        // 运行10次后销毁当前定时器
        if($count++ >= 10) {
            echo "Timer::del($timer_id)\n";
            Timer::del($timer_id);
        }
    });
};

Worker::runAll();
