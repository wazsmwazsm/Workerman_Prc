<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use GlobalData\Server;

/****  实例化一个\GlobalData\Server服务 ****/

// 监听端口
$worker = new Server('0.0.0.0', 2207);

Worker::runAll();
