<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

class Mail {
    public function send($to, $content, $timer_id) {
        // 临时给当前对象添加一个count属性，记录定时器运行次数
        $this->count = empty($this->count) ? 1 : $this->count;
        // 运行10次后销毁当前定时器
        echo "send mail {$this->count} to $to : $content ...\n";
        if($this->count++ >= 10) {
            echo "Timer::del($timer_id)\n";
            Timer::del($timer_id);
        }
    }
}

$worker = new Worker();
$worker->onWorkerStart = function($worker) {
    $mail = new Mail();
    $timer_id = Timer::add(1, [$mail, 'send'], ['workerman@workerman.net', 'hello workerman', &$timer_id]);
};

Worker::runAll();
