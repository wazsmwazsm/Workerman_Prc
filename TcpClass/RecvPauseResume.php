<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Lib\Timer;

/*
    void Connection::pauseRecv(void)

      使当前连接停止接收数据。该连接的onMessage回调将不会被触发。此方法对于上传流量控制非常有用

    void Connection::resumeRecv(void)

      使当前连接继续接收数据。此方法与Connection::pauseRecv配合使用，对于上传流量控制非常有用
*/

// 请求限制
const LIMIT = 100;

$worker = new Worker('text://0.0.0.0:600');

$worker->onConnect = function($con) {
    // 给 connection 对象动态添加一个属性，用来保存当前连接发送多少个请求
    $con->msgCount = 0;
};

$worker->onMessage = function($con, $data) {
    // 每个连接接收 100 个请求就不载接收数据
    if(++$con->msgCount > LIMIT) {
        $con->pauseRecv();
        // 30s 后恢复接收
        Timer::add(30, function($con) {
            $con->resumeRecv();
        }, array($con), FALSE);
    }
};
// 运行worker
Worker::runAll();
