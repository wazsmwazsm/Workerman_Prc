<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

// 创建 worker 容易，不进行任何监听
$worker = new Worker();

$worker->onWorkerStart = function($worker) {
    // 设置访问对方主机的本地 ip 及端口(每个 socket 连接都会占用一个本地端口)
    // 自己作为客户端的设置
    $context_option = [
        'socket' => [
            // ip必须是本机网卡 ip，并且能访问对方主机，否则无效
            'bindto' => '192.168.10.10:233',
        ],
    ];

    // 这里是对方主机的地址和端口设置。当然这里不用 $context_option 也可以，
    // 程序会默认选择一个端口进行数据传输
    $con = new AsyncTcpConnection('ws://127.0.0.1:600', $context_option);

    $con->onConnect = function($con) {
        $con->send('hello');
    };

    $con->onMessage = function($con, $data) {
        echo $data;
    };
    // 启动连接
    $con->connect();
};
// 运行worker
Worker::runAll();
