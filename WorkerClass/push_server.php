<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

/*
  示例 php后端及时推送消息给客户端

    原理：

    1、建立一个 websocket Worker，用来维持客户端长连接

    2、websocket Worker 内部建立一个 text Worker

    3、websocket Worker 与 text Worker 是同一个进程，可以方便的共享客户端连接

    4、某个独立的 php 后台系统通过 text 协议与 text Worker 通讯

    5、text Worker 操作 websocket 连接完成数据推送
*/

$worker = new Worker('websocket://0.0.0.0:6788');
$worker->count = 1;

// $worker->onWorkerStart = function($worker) {
//     $inner_text_worker = new Worker('text://0.0.0.0:600');
//     $inner_text_worker->onMessage = function($con, $data) {
//         echo 'a';
//     };
// };

// 新增属性，保存 uid 和 connection 的映射关系
$worker->uidConnections = [];
$worker->onMessage = function($con, $data) use(&$worker) {
    // 判断客户端是否已经验证，没有验证把第一个包当做 uid (为了演示方便)
    if( ! isset($con->uid)) {
        $con->uid = $data;
        // 保存 uid 和 连接 的映射，方便查找连接推送消息
        $worker->uidConnections[$con->uid] = $con;
    }

    // 广播消息
    broadcast($worker, 'user '.$con->uid.' online');
};

$worker->onClose = function($con) use(&$worker) {
    if(isset($con->uid)) {
        // 删除映射
        unset($woker->uidConnections[$con->uid]);
    }
    // 广播消息
    broadcast($worker, 'user '.$con->uid.' offline');
};

// 向所有验证用户推送数据
function broadcast($worker, $msg) {
    foreach($worker->uidConnections as $connection) {
        $connection->send($msg);
    }
}

// 针对 uid 进行推送
function sendMsgByUid($worker, $uid, $msg) {
    if(isset($worker->uidConnections[$uid])) {
        $con = $worker->uidConnections[$uid];
        $con->send($msg);

        return TRUE;
    }

    return FALSE;
}

Worker::runAll();
