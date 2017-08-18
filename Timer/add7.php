<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

class Mail {
    // 作为回调函数，必须为 public
    public function send($to, $content) {
        echo "send mail to ".$to." $content \n";
    }

    public function sendLater($to, $content) {
        // 调用方法属于当前类，使用 $this
        Timer::add(4, [$this, 'send'], [$to, $content], FALSE);
    }
}

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $mail = new Mail();
    $to = 'workerman@workerman.net';
    $content = 'hello workerman';
    $mail->sendLater($to, $content);
};

Worker::runAll();
