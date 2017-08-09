<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

// 以守护进程模式运行
// 此属性为全局静态属性，表示是否以daemon(守护进程)方式运行。
// 如果启动命令使用了 -d参数，则该属性会自动设置为true。也可以代码中手动设置。
Worker::$daemonize = true;

$worker = new Worker('text://0.0.0.0:600');

$worker->onWorkerStart = function($worker)
{
    echo "Worker start\n";
};

// 启动 worker
Worker::runAll();
