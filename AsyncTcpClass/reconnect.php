<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

/*
reConnect 方法
  void AsyncTcpConnection::reConnect(float $delay = 0)
    (要求Workerman版本>=3.3.5)

    重连。一般在onClose回调中调用，实现断线重连。
    由于网络问题或者对方服务重启等原因导致连接断开，则可以通过调用此方法实现重连。

  参数
    $delay

    延迟多久后执行重连。单位为秒，支持小数，可精确到毫秒。
    如果不传或者值为0则代表立即重连。
    最好传递参数让重连延迟执行，避免因为对端服务问题一直不可连导致本机cpu消耗过高。

  返回值
    无返回值
*/
$worker = new Worker();

$worker->onWorkerStart = function($worker) {

    $con = new AsyncTcpConnection('tcp://127.0.0.1:600');

    $con->onConnect = function($con) {
        $con->send('hello');
    };

    $con->onMessage = function($con, $msg) {
        echo "recv $msg\n";
    };

    $con->onClose = function($con) {
        // 如果断开连接，在 1 秒后重连
        $con->reConnect(1);
    };

    $con->connect();
};

Worker::runAll();
