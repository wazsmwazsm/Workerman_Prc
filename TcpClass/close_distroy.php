<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;

$worker = new Worker('text://0.0.0.0:600');
$worker->onMessage = function($con, $data) {

    // 调用close会等待发送缓冲区的数据发送完毕后才关闭连接，并触发连接的onClose回调。
    if($data == 'close') {
        $con->close("con:".$con->id." will close soon\n");
    }

    // 与close不同之处是，调用destroy后即使该连接的发送缓冲区还有数据未发送到对端，
    // 连接也会立刻被关闭，并立刻触发该连接的onClose回调。
    if($data == 'destroy') {
        $con->destroy();
    }
};

$worker->onClose = function($con) {
    echo "con:".$con->id." closed\n";
};

Worker::runAll();
