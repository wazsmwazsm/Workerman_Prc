<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Channel\Server;
use Channel\Client;

// 初始化一个 channel 服务端
$channel_server = new Server('0.0.0.0', 2206);
// websocket 服务器
$worker = new Worker('0.0.0.0:600');
$worker->count = 2;
$worker->name = 'pusher';
$worker->onWorkerStart = function($worker) {
    // channel 客户端
    Client::connect('127.0.0.1', 2206);
    // 以 worker 的进程 ID 作为事件名称
    $event_name = $worker->id;
    // 订阅事件
    Client::on($event_name, function($event_data) use($worker) {

    });
};
