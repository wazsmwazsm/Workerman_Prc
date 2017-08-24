<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Channel\Server;
use Channel\Client;

// 初始化一个 channel 服务端
$channel_server = new Server('0.0.0.0', 2206);
// websocket 服务器
$worker = new Worker('websocket://0.0.0.0:600');
$worker->count = 2;
$worker->name = 'pusher';
$worker->onWorkerStart = function($worker) {
    // channel 客户端
    Client::connect('127.0.0.1', 2206);
    // 以 worker 的进程 ID 作为事件名称
    $event_name = $worker->id;
    // 订阅 worker id 事件 （给每个 websocket worker 订阅，channel 服务端会保存这些连接）
    Client::on($event_name, function($event_data) use($worker) {
        $to_con_id = $event_data['to_con_id'];
        $msg = $event_data['content'];
        if( ! isset($worker->connections[$to_con_id])) {
            echo "connection not exist\n";
            return;
        }
        $to_con = $worker->connections[$to_con_id];
        $to_con->send($msg);
    });
    // 订阅广播事件 （给每个 websocket worker 订阅，channel 服务端会保存这些连接）
    $event_name = "broadcast";
    Client::on($event_name, function($event_data) use ($worker) {
        $msg = $event_data['content'];
        foreach ($worker->connections as $con) {
            $con->send($msg);
        }
    });
};

$worker->onConnect = function($con) use($worker) {
    $msg = "workerID:{$worker->id} connectionID:{$con->id} connected\n";
    $con->send($msg);
};
// 用来处理 http 请求，向任意客户端推送数据，需要传 workerID 和 connectionID
$http_worker = new Worker('http://0.0.0.0:4437');
$http_worker->name = 'publisher';
$http_worker->onWorkerStart = function() {
    // 这里为什么还要连接，是因为 http_worker 和 websocket 进程不在一个进程中
    // 底层的 ptncl 对不同的 worker 每次都 fork 新进程
    // 要想 http_worker 进程中访问服务器，必须连接一下
    // 不加这句的话，每次用的是默认的端口和 IP，如果 server 不是就连不上了
    Client::connect('127.0.0.1', 2206);
};
$http_worker->onMessage = function($con, $data) {
    $con->send('ok');
    // 没有发送内容
    if(empty($_GET['content'])) {
        return;
    }
    // 如果是向某个 worker 连接推送数据
    if(isset($_GET['to_worker_id']) && isset($_GET['to_con_id'])) {
        $event_name = $_GET['to_worker_id'];
        $to_con_id = $_GET['to_con_id'];
        $content = $_GET['content'];
        Client::publish($event_name, [
            'to_con_id' => $to_con_id,
            'content' => $content,
        ]);
    } else {
        // 全局广播
        $event_name = 'broadcast';
        $content = $_GET['content'];
        Client::publish($event_name, ['content' => $content]);
    }
};

Worker::runAll();

// 测试
// http://192.168.10.10:4437/?content=hello-woker8%20worker8&to_worker_id=0&to_con_id=8
// http://192.168.10.10:4437/?content=hello
