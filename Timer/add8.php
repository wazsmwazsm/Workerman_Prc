<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

class Mail {
    // 静态方法
    public static function send($to, $content) {
        echo "send mail to ".$to." $content \n";
    }
}

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $to = 'workerman@workerman.net';
    $content = 'hello workerman';
    Timer::add(2, ['Mail', 'send'], [$to, $content], FALSE);
};

Worker::runAll();
