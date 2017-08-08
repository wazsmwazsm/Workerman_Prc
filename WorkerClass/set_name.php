<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

$worker = new Worker('websocket://0.0.0.0:600');
// 设置当前Worker实例的名称，方便运行status命令时识别进程。不设置时默认为none。
$worker->name = 'MyWebsocketWorker';

$worker->onWorkerStart = function($worker) {
    echo "Worker starting...\n";
};

// 启动 worker
Worker::runAll();
