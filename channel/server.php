<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Channel\Server;

/*
  Channel是一个分布式通讯组件，用于完成进程间通讯或者服务器间通讯。
  不使用 channel 的话，虽然每个 worker 进程间的各个连接可以相互通信
  但是多个进程之间无法进行数据传输，channel 组件解决了这个问题

  特点

    1、基于订阅发布模型

    2、非阻塞式 IO

  原理

    Channel包含 Channel/Server 服务端和 Channel/Client 客户端

    Channel/Client 通过 connect 接口连接 Channel/Server 并保持长连接

    Channel/Client 通过调用 on 接口告诉 Channel/Server 自己关注哪些事件，并注册事件回调函数（回调发生在 Channel/Client 所在进程中）

    Channel/Client 通过 publish 接口向 Channel/Server 发布某个事件及事件相关的数据

    Channel/Server 接收事件及数据后会分发给关注这个事件的 Channel/Client

    Channel/Client 收到事件及数据后触发 on 接口设置的回调

    Channel/Client 只会收到自己关注事件并触发回调

*/

// 不传参数默认是监听0.0.0.0:2206
$channel_server = new Server();

// 防止多次启动 多文件时使用
if( ! defined('GLOBAL_START')) {
    Worker::runAll();
}
