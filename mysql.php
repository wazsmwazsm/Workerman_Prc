<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\MySQL;

/*
  常驻内存的程序在使用 mysql 时经常会遇到 mysql gone away 的错误，
  这个是由于程序与 mysql 的连接长时间没有通讯，连接被 mysql 服务端踢掉导致。
  本数据库类可以解决这个问题，当发生 mysql gone away 错误时，会自动重试一次。

  强烈建议在 onWorkerStart 回调中初始化数据库连接，避免在 Worker::runAll();
  运行前就初始化连接，在 Worker::runAll();运行前初始化的连接属于主进程，
  子进程会继承这个连接，主进程和子进程共用相同的数据库连接会导致的错误。
*/

class DB {
    public static $instance;
}

$worker = new Worker('tcp://0.0.0.0:600');
$worker->onWorkerStart = function($worker) {
    DB::$instance = new Mysql\Connection('127.0.0.1', '3306', 'homestead', 'secret', 'homestead');
};

$worker->onMessage = function($con, $data) {
    $con->send(json_encode(DB::$instance->query($data)));
};

Worker::runAll();
