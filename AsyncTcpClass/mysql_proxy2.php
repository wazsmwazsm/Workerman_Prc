<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

// 使用 pipe 做 mysql 的 TCP 代理

$worker = new Worker('tcp://0.0.0.0:600');
$worker->count = 12;

$worker->onConnect = function($con) {
    $con_to_mysql = new AsyncTcpConnection('tcp://127.0.0.1:3306');

    $con->pipe($con_to_mysql);
    $con_to_mysql->pipe($con);

    $con_to_mysql->connect();
};

Worker::runAll();
// 执行 mysql -uhomestead -P600 -h127.0.0.1 -p 进行访问，可以正常访进入数据库
