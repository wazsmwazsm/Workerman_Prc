<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;

$worker = new Worker('tcp://0.0.0.0:600');

$worker->onConnect = function($con) {
    // 连接的id。这是一个自增的整数
    // workerman是多进程的，每个进程内部会维护一个自增的 connection id 列表，
    // 所以多个进程之间的 connection id 会有重复。
    // 如果想要不重复的 connection id 可以根据需要给 connection->id 重新赋值，
    // 例如加上 worker->id 前缀
    echo 'connection id : '.$con->id." \n";

    // 手动设置当前连接的协议类
    $con->protocol = 'Workerman\\Protocols\\Http';
};


// 运行worker
Worker::runAll();
