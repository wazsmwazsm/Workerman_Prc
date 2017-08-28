<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Lib\Timer;
/*
  心跳作用主要有两个：

  1、客户端定时给服务端发送点数据，防止连接由于长时间没有通讯而被某些节点的防火墙关闭导致连接断开的情况。

  2、服务端可以通过心跳来判断客户端是否在线，如果客户端在规定时间内没有发来任何数据，就认为客户端下线。这样可以检测到客户端由于极端情况(断电、断网等)下线的事件。
*/
// 心跳间隔
define('HEARTBEAT_TIME', 25);

$worker = new Worker('text://0.0.0.0:600');
$worker->onMessage = function($con, $data) {
    // 给 connection 临时设置一个 lastMessageTime 属性，用来记录上次收到消息的时间
    $con->lastMsgTime = time();
};

$worker->onWorkerStart = function($worker) {
    // 设置定时器，进行存活监测
    Timer::add(1, function() use($worker) {
        $time_now = time();
        foreach ($worker->connections as $con) {
            // 没收到消息的连接设置时间为当前时间
            if(empty($con->lastMessageTime)) {
                $con->lastMessageTime = $time_now;
                continue;
            }
            // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
            if($time_now - $con->lastMessageTime > HEARTBEAT_TIME) {
                $con->close();
            }
        }
    });
};

Worker::runAll();
