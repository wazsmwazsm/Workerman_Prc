<?php

use Workerman\Worker;
require_once __DIR__ . '/Workerman/Autoloader.php';

// 全局变量，用来分配进程 ID
$global_uid = 0;

// 建立主进程
$text_worker = new Worker("text://0.0.0.0:600");

// 只启动 1个进程，这样方便客户端之间传输数据
$text_worker->count = 1;

// 客户端连上来时分配uid，并保存连接，并通知所有客户端
$text_worker->onConnect = function($conn)
{
    global $text_worker, $global_uid;
    // 为当前连接分配一个 uid, 方便识别用户
    $conn->uid = ++$global_uid;
};

// 当客户端发送消息过来时，转发给所有人
$text_worker->onMessage = function($conn, $data)
{
    global $text_worker;
    foreach ($text_worker->connections as $connection)
    {
        $connection->send("user[{$conn->uid}] said: $data");
    }
};

// 当客户端断开时，广播给所有客户端
$text_worker->onClose = function($conn)
{
    global $text_worker;
    foreach($text_worker->connections as $connection)
    {
        $connection->send("user[{$conn->uid}] logout");
    }
};

// 运行
Worker::runAll();
