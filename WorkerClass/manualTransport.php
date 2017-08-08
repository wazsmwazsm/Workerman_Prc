<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

$worker = new Worker('text://0.0.0.0:600');
// 手动修改传输层协议
$worker->transport  = 'udp';

$worker->onMessage = function($connection, $data) {
    $connection->send('Hello '.$data);
};

// 启动 worker
Worker::runAll();
