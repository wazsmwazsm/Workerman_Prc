<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Channel\Server;
use Channel\Client;

// 初始化一个 channel 服务端
$channel_server = new Server('0.0.0.0', 2206);
// websocket 服务器
$worker = new Worker('websocket://0.0.0.0:600');
$worker->count = 8;
// 全局数组，用来保存 group 和 con 的映射
$group_con_map = [];
$worker->onWorkerStart = function() {
    // Channel 客户端连接到 Channel 服务端
    Client::connect('127.0.0.1', 2206);
    // 订阅全局分组发送事件
    Client::on('send_to_group', function($event_data) {
        $group_id = $event_data['group_id'];
        $message = $event_data['message'];
        global $group_con_map;
        // 按组发送
        if(isset($group_con_map[$group_id])) {
            foreach ($group_con_map[$group_id] as $con) {
                $con->send($message);
            }
        }
    });
};

$worker->onMessage = function($con, $data) {
    $data = json_decode($data, true);
    $cmd = $data['cmd'];
    $group_id = $data['group_id'];
    switch($cmd) {
        // 连接加入群组
        case 'add_group':
            global $group_con_map;
            // 将连接加入到对应的群组数组中
            $group_con_map[$group_id][$con->id] = $con;
            // 记录这个连接加入了哪些群组，方便在 onclose 的时候清理 group_con_map 对应群组的数据
            $con->group_id = isset($con->group_id) ? $con->group_id : [];
            $con->group_id[$group_id] = $group_id;
            break;
        // 群发给群组
        case 'send_to_group':
            // 给群中的连接群发数据
            Client::publish('send_to_group', [
                'group_id' => $group_id,
                'message'  => $data['message'],
            ]);
            break;
    }
};
// 防止内存泄漏，连接关闭时从全局数组中删除数据
$worker->onclose = function($con) {
    global $group_con_map;
    // 遍历连接加入的所有群，然后从 $group_con_map 中删除
    if(isset($con->group_id)) {
        foreach ($con->group_id as $group_id) {
            unset($group_con_map[$group_id][$con->id]);
        }
        // 如果群里没有连接、删除群
        if(empty($group_con_map[$group_id])) {
            unset($group_con_map[$group_id]);
        }
    }
};

Worker::runAll();
