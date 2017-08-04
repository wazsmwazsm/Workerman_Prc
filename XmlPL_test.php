<?php

use Workerman\Worker;
require_once __DIR__ . '/vendor/autoload.php';

// 创建一个 worker , 监听指定端口, http 协议通信
$xmlpl_worker = new Worker("XmlPL://0.0.0.0:600");

// 启动 10 个进程
$xmlpl_worker->count = 10;

// 响应函数
$xmlpl_worker->onMessage = function($con, $data) {
    /*
      发送样例:
      0000000121<?xml version="1.0" encoding="ISO-8859-1"?><request><module>user</module><action>getInfo</action></request>
    */
    $con->send('module: '.$data->module.', action: '.$data->action);
};

// 启动 worker
Worker::runAll();
