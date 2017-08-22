<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use GlobalData\Server;
use GlobalData\Client;
/*
  进程间数据共享组件，用于分布式数据共享。服务端基于 Workerman。
  可以对 server 的共享变量进行原子操作
  客户端可用于任何 PHP 项目(可用于 php-fpm)。
*/
// GlobalData server
$global_worker = new Server('0.0.0.0', 2207);

$worker = new Worker('tcp://0.0.0.0:600');
$worker->onWorkerStart = function() {
    // 初始化一个全局的 GlobalData client
    global $global;
    $global = new Client('127.0.0.1:2207');
};

$worker->onMessage = function($con, $data) {
    // 更改全局共享变量的值，其他进程会共享这个变量
    global $global;
    echo "now global->somdata=".var_export($global->somdata, true)."\n";
    echo "set \$global->somedata=$data";
    $global->somedata = $data;
};

Worker::runAll();
