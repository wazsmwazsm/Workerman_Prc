<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;

/*
  作用与 Worker 的回调相同，区别是只针对当前连接有效，
  也就是可以针对某个连接的设置回调。
*/

$worker = new Worker('text://0.0.0.0:600');
// onConnect 回调属性用来对连接进行初始化设置
$worker->onConnect = function($con) {

    /***************   针对单独连接进行设置   ******************/

    $con->onMessage = function($con, $data) {
        $con->send($data);
    };

    $con->onClose = function($con) {
        echo "connection closed\n";
    };

    $con->onBufferFull = function($con) {
        echo "bufferFull and do not send again\n";
    };

    $con->onBufferDrain = function($con) {
        echo "buffer drain and continue send\n";
    };

    $con->onError = function($con, $code, $msg) {
        echo "error $code $msg\n";
    };
};
// 运行worker
Worker::runAll();
