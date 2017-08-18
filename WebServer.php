<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\WebServer;

// 监听 8080
$webServer = new WebServer('http://0.0.0.0:8080');
// 域名、根目录映射
$webServer->addRoot('www.worker.app', '/home/vagrant/workerweb/www');
$webServer->addRoot('blog.worker.app', '/home/vagrant/workerweb/blog');
// 设置开启多少进程
$webserver->count = 4;

Worker::runAll();
