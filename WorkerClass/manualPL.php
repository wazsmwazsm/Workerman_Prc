<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

$worker = new Worker('tcp://0.0.0.0:600');
// 手动修改协议 (命名空间)
$worker->protocol = 'Workerman\\Protocols\\Http';

$worker->onMessage = function($connection, $data) {
    
    $connection->send(json_encode($_GET));
    $connection->send(json_encode($_POST));
};

// 启动 worker
Worker::runAll();
