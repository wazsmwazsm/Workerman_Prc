<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;

class A {
  public static $test;
}

// 创建一个 worker , 监听指定端口, http 协议通信
$worker = new Worker("http://0.0.0.0:600");

// 启动 1 个进程
$worker->count = 4;

$worker->onWorkerStart = function($worker) {
    // 每个进程 fork 的时候都给 test 赋值
    // 由于子进程都是 fork 的父进程，属于复制，装进内存执行的
    // 内容都是互相独立的
    A::$test = $worker->id;
};

// 响应函数
$worker->onMessage = function($con, $data) {
    // 并发的时候可以看到，每个进程中其实都有一个 A 类，static 数据在进程中是不共享的
    var_dump(A::$test);
    $con->send('a');
};

// 启动 worker
Worker::runAll();
