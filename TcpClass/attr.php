<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\TcpConnection;

// 此属性为全局静态属性，用来设置每个连接能够接收的最大包包长。不设置默认为10MB。
TcpConnection::$maxPackageSize = 1024000;

// 此属性为全局静态属性，用来设置所有连接的默认应用层发送缓冲区大小。不设置默认为1MB
TcpConnection::$defaultMaxSendBufferSize = 2*1024*1024;

$worker = new Worker('tcp://0.0.0.0:600');

$worker->onConnect = function($con) {
    // 连接的id。这是一个自增的整数
    // workerman是多进程的，每个进程内部会维护一个自增的 connection id 列表，
    // 所以多个进程之间的 connection id 会有重复。
    // 如果想要不重复的 connection id 可以根据需要给 connection->id 重新赋值，
    // 例如加上 worker->id 前缀
    echo 'connection id : '.$con->id." \n";

    // 手动设置当前连接的协议类
    $con->protocol = 'Workerman\\Protocols\\Text';
    // 设置当前连接的应用层发送缓冲区大小,
    // 不设置默认为Connection::$defaultMaxSendBufferSize(1MB)。
    // Connection::$maxSendBufferSize 和 Connection::$defaultMaxSendBufferSize均可以动态设置。
    // 此属性影响onBufferFull回调
    $con->maxSendBufferSize = 102400;
};
$worker->onMessage = function($connection, $data) {
    // 获取当前 connection 对象所属的 worker 实例
    foreach ($connection->worker->connections as $con) {
        $con->send($data);
    }
};

// 运行worker
Worker::runAll();
