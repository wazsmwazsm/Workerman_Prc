<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;

// 证书最好是申请的证书
$context = array(
    'ssl' => array(
        'local_cert' => '/etc/nginx/conf.d/ssl/server.pem', // 也可以是crt文件
        'local_pk'   => '/etc/nginx/conf.d/ssl/server.key',
    )
);
// 这里设置的是websocket协议
$worker = new Worker('websocket://0.0.0.0:4431', $context);
// 设置transport开启ssl，websocket+ssl即wss
$worker->transport = 'ssl';
$worker->onMessage = function($con, $msg) {
    $con->send('ok');
};

Worker::runAll();
