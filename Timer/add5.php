<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

// 普通函数
function send_mail($to, $content) {
    echo "send mail to ".$to." $content \n";
}

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $to = 'workerman@workerman.net';
    $content = 'hello workerman';
    // 只运行一次，第四个参数设置为 FALSE
    Timer::add(2, 'send_mail', [$to, $content], FALSE);
};

Worker::runAll();
