<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

// 以守护进程模式运行
// 此属性为全局静态属性，表示是否以daemon(守护进程)方式运行。
// 如果启动命令使用了 -d参数，则该属性会自动设置为true。也可以代码中手动设置。
Worker::$daemonize = true;

// 所有的打印输出全部保存在/tmp/stdout.log文件中
/*
  此属性为全局静态属性，如果以守护进程方式(-d启动)运行，
  则所有向终端的输出(echo var_dump等)都会被重定向到stdoutFile指定的文件中。
  如果不设置，并且是以守护进程方式运行，则所有终端输出全部重定向到/dev/null
*/
Worker::$stdoutFile = '/tmp/stdout.log';

$worker = new Worker('text://0.0.0.0:600');

$worker->onWorkerStart = function($worker)
{
    echo "Worker start\n";
};

// 启动 worker
Worker::runAll();
