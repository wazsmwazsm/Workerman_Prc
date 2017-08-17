<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

// 创建 worker 容易，不进行任何监听
$worker = new Worker();

$worker->onWorkerStart = function($worker) {
    // 不直接用 http 协议，使用 tcp 模拟 http 协议发送数据
    $con_to_baidu = new AsyncTcpConnection('tcp://www.baidu.com:80'); // 向百度的 80 端口发起 tcp 请求
    // 建立连接成功是，发送 http 请求
    $con_to_baidu->onConnect = function($con) {
        echo "connect success\n";
        // http1.1 Host 字段指定了主机名，解决一个 IP 下多个虚拟主机或者反向代理有多台服务器的情况
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
    // 开启连接，注意开启要放在设置回调之后
    $con_to_baidu->connect();
};

// 运行worker
Worker::runAll();
