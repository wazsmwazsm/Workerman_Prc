<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker('tcp://0.0.0.0:600');
// 开启多少进程则开多少定时任务，注意业务逻辑
$worker->count = 1;
$worker->onConnect = function($con) {
    $con_time = date('Y-m-d H:i:s', time())."\n";
    // 保存 timer_id 方便删除定时器
    // 需要写在闭包函数的形参中
    $con->timer_id = Timer::add(1, function($con, $con_time) {
        $con->send($con_time);
    }, [$con, $con_time]); // 通过 add 的参数传递
};

// 关闭连接时，删除相应的定时器
$worker->onClose = function($con) {
    Timer::del($con->timer_id);
};

Worker::runAll();
