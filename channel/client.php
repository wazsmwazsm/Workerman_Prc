<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Channel\Client;

$worker = new Worker('websocket://0.0.0.0:600');
$worker->name = 'websocket';
$worker->count = 4;
// 开启 worker 进程时创建 channel 连接、订阅事件
$worker->onWorkerStart = function($worker) {
    /*
      void \Channel\Client::connect([string $listen_ip = '127.0.0.1', int $listen_port = 2206])

      参数
        listen_ip
        Channel/Server 监听的ip地址，不传默认是127.0.0.1

        listen_port
        Channel/Server监听的端口，不传默认是2206

      返回值  void
    */
    // channel 客户端连接到 channel 服务端
    // 看 channel 组件的代码 : 原理就是通过多个 worker 进程个开一个 channel 客户端
    // channel 客户端向 channel 服务端发送事件，channel 服务端将所有的客户端的连接、事件
    // 保存起来，然后由 channel 客户端的不同动作进行广播事件(发送信息给收藏的连接)，达到
    // 多进程通信
    Client::connect('127.0.0.1', 2206);
    /*
      void \Channel\Client::on(string $event_name, callback $callback_function)
      订阅$event_name事件并注册事件发生时的回调$callback_function

      回调函数的参数
        $event_name
        订阅的事件名称，可以是任意的字符串。

        $callback_function
        事件发生时触发的回调函数。函数原型为callback_function(mixed $event_data)
        。$event_data是事件发布(publish)时传递的事件数据。

      注意：
        如果同一个事件注册了两个回调函数，后一个回调函数将覆盖前一个回调函数。
    */
    // 订阅 broadcast 事件，并注册事件回调
    Client::on('broadcast', function($event_data) use($worker) {
        // 事件的动作 : 像当前 worker 进程的所有客户端广播消息
        foreach ($worker->connections as $con) {
            $con->send($event_data);
        }
    });
    // Client::unsubscribe('broadcast'); // 放到这里会取消掉所有 worker 进程的订阅
};
// 收到消息时发布事件，执行设置的回调函数
$worker->onMessage = function($con, $data) {
    // 将客户端发来的数据当做事件数据
    $event_data = $data;

    /*
      void \Channel\Client::unsubscribe(string $event_name)
      取消订阅某个事件，这个事件发生时将不会再触发on($event_name, $callback)注册的回调 $callback
    */
    // 如果接收到种止信息，取消事件订阅
    if($event_data == 'exit') {
        // 查看源码，会发现执行 unsubscribe 后会直接把 on 的回调函数
        // 设置为 null，所以下面的 publish 也不管用了
        // 因为是常驻内存，一个 worker 中的 Client 类的静态属性修改后
        // 会保持这个值在内存中
        // 看 channel 的 server 代码，适用 unsubscribe 只取消了当前进程的消息订阅
        // 除非在 onWorkerStart 中才能对所有的订阅进行取消
        Client::unsubscribe('broadcast');
    } else {
        /*
          void \Channel\Client::publish(string $event_name, mixed $event_data)
          发布某个事件，所有这个事件的订阅者会收到这个事件并触发on($event_name, $callback)注册的回调$callback

          参数
            $event_name
            发布的事件名称，可以是任意的字符串。如果事件没有任何订阅者，事件将被忽略。

            $event_data
            事件相关的数据，可以是数字、字符串或者数组
        */
        // 向所有 worker 进程发布 broadcast 事件 看 channel 的 server 代码，这个是针对所有连接的
        Client::publish('broadcast', $event_data);
    }



};

Worker::runAll();
