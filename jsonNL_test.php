<?php
use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

// 创建一个 worker , 监听指定端口, http 协议通信
$tcp_worker = new Worker("JsonNL://0.0.0.0:600");

// 启动 10 个进程
$tcp_worker->count = 10;

// 响应函数
$tcp_worker->onMessage = function($con, $data) {

    foreach ($data as $key => $value) {
      $con->send($key.' : '.$value);
    }
};

// 启动 worker
Worker::runAll();

/*
  打开 telnet 测试，只有输入 json 字符串才可以被识别为 JsonNL 协议，能够解析、返回
*/