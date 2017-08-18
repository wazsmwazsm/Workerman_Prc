<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker();
// 开启多少进程则开多少定时任务，注意业务逻辑
$worker->count = 5;
$worker->onWorkerStart = function($worker) {
    // 只在 id 编号为 0 的进程上设置定时器
    if($worker->id === 0) {
        Timer::add(1, function() use($worker) {
            echo "{$worker->id} run\n";
        });
    }
};

Worker::runAll();
