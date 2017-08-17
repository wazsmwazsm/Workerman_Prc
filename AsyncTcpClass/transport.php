<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

/*
  设置传输属性，可选值为 tcp 和 ssl，默认是tcp。
  transport为 ssl 时，要求PHP必须安装openssl扩展。
*/

$worker = new Worker();

$worker->onWorkerStart = function($worker) {

    $con_to_baidu = new AsyncTcpConnection('tcp://www.baidu.com:443');
    // 设置为 ssl 加密连接
    $con_to_baidu->transport = 'ssl';

    $con_to_baidu->onConnect = function($con) {
        echo "connect success\n";
        $con->send("GET / HTTP/1.1\r\nHost: www.baidu.com\r\nConnection: keep-alive\r\n\r\n");
    };

    $con_to_baidu->onMessage = function($con, $http_buffer) {
        echo $http_buffer;
    };

    $con_to_baidu->onClose = function($con) {
        echo "connection closed\n";
    };

    $con_to_baidu->onError = function($con, $code, $msg) {
        echo "Error code:$code msg:$msg\n";
    };
    // 启动连接
    $con_to_baidu->connect();
};

Worker::runAll();
