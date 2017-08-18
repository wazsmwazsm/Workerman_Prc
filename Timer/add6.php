<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

class Mail {
    // 作为回调函数，必须为 public
    public function send($to, $content) {
        echo "send mail to ".$to." $content \n";
    }
}

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $mail = new Mail();
    $to = 'workerman@workerman.net';
    $content = 'hello workerman';
    // 只运行一次，第四个参数设置为 FALSE
    Timer::add(2, [$mail, 'send'], [$to, $content], FALSE);
};

Worker::runAll();
