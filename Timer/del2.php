<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $timer_id = Timer::add(1, function() use(&$timer_id) {
        static $i = 0;
        echo $i++."\n";
        // 运行 10 次后删除定时器
        if($i == 10) {
            echo "Timer::del($timer_id)\n";
            Timer::del($timer_id);
        }
    });
};

Worker::runAll();
