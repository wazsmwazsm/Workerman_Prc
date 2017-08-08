<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

// worker实例1有4个进程，进程id编号将分别为0、1、2、3
$worker1 = new Worker('tcp://0.0.0.0:600');
// 设置启动 4 个进程
$worker1->count = 4;
// 每个进程启动后打印当前进程 id 编号即 $worker1->id
$worker1->onWorkerStart = function($worker1) {
    echo "worker1->id={$worker1->id}\n";
};

// worker实例2有两个进程，进程id编号将分别为0、1
$worker2 = new Worker('tcp://0.0.0.0:601');
// 设置启动2个进程
$worker2->count = 2;
// 每个进程启动后打印当前进程id编号即 $worker2->id
$worker2->onWorkerStart = function($worker2) {
    echo "worker2->id={$worker2->id}\n";
};

// 启动 worker
// 多个进程的状态会依次从主进程 fork 出子进程
Worker::runAll();
