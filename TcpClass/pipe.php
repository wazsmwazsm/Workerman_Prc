<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

/*
pipe
  说明:
    void Connection::pipe(TcpConnection $target_connection)

  参数
    将当前连接的数据流导入到目标连接。内置了流量控制。此方法做 TCP 代理非常有用
*/

$worker = new Worker('tcp://0.0.0.0:600');
$worker->count = 12;

$worker->onConnect = function($con) {
    // 建立本地 80 端口的异步连接
    $con_to_80 = new AsyncTcpConnection('tcp://127.0.0.1:80');
    // 当前客户端数据导向 80 端口, 设置两个连接互相关联
    // 现在请求本地 tcp 600 端口相当于在请求本地的 tcp 80 端口
    $con->pipe($con_to_80);
    $con_to_80->pipe($con);
    // 执行异步连接
    $con_to_80->connect();
};

Worker::runAll();
/*
  TCP 80 端口发起 http 请求

GET /ad/ratio/getNativeAd?package_name=com.sg.squareeditor&channel=googleplay&statue=2 HTTP/1.1
Host: material.app
User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.6)
Gecko/20050225 Firefox/1.0.1
Connection: Keep-Alive

*/
