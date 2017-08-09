<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

/*

  用来指定workerman日志文件位置。

  此文件记录了workerman自身相关的日志，包括启动、停止等。

  如果没有设置，文件名默认为workerman.log，文件位置位于Workerman的上一级目录中。

  注意：

  这个日志文件中仅仅记录workerman自身相关启动停止等日志，不包含任何业务日志。

  业务日志类可以利用file_put_contents 或者 error_log 等函数自行实现。
*/
Worker::$logFile = '/tmp/workerman.log';

$worker = new Worker('text://0.0.0.0:600');

$worker->onWorkerStart = function($worker)
{
    echo "Worker start\n";
};

// 启动 worker
Worker::runAll();
