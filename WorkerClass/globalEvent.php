<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Events\EventInterface;

// 此属性为全局静态属性，为全局的eventloop实例
// 可以向其注册文件描述符的读写事件或者信号事件。

$worker = new Worker();

$worker->onWorkerStart = function($worker)
{
    // 打印出进程的 PID ，方便手动结束
    echo "Pid is ".posix_getpid()."\n";
    // 此属性为全局静态属性，为全局的eventloop实例
    // 可以向其注册文件描述符的读写事件或者信号事件。
    // 当进程收到 SIGALRM 信号时，打印输出一些信息
    Worker::$globalEvent->add(SIGALRM, EventInterface::EV_SIGNAL, function() {
        echo "Get signal SIGALRM\n";
    });
};

// 启动 worker
Worker::runAll();

// 客户端运行 kill -SIGALRM pid
