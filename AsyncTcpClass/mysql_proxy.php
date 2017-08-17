<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

/* 本实例用 pipe 也可以实现 */

// mysql 地址
const MYSQL_ADDR = 'tcp://127.0.0.1:3306';

// 开启代理容器
$proxy = new Worker('tcp://0.0.0.0:600');

$proxy->onConnect = function($con) {

    /*******   mysql 连接设置   ********/

    // 建立到 mysql 的连接
    $con_to_mysql = new AsyncTcpConnection(MYSQL_ADDR);
    // mysql 连接发来数据时转发给连接代理的客户端
    $con_to_mysql->onMessage = function($con_to_mysql, $buffer) use ($con) {
        $con->send($buffer);
    };
    //  mysql 连接关闭时，关闭对应的代理到客户端的连接
    $con_to_mysql->onClose = function($con_to_mysql) use ($con) {
        $con->close();
    };
    // mysql连接上发生错误时，关闭对应的代理到客户端的连接
    $con_to_mysql->onError = function($con_to_mysql) use ($con) {
        $con->close();
    };
    // 执行异步连接
    $con_to_mysql->connect();

    /*******   代理连接设置   ********/
    // 客户端发来数据时，转发给相应的 mysql 连接
    $con->onMessage = function($con, $buffer) use($con_to_mysql) {
        $con_to_mysql->send($buffer);
    };
    // 客户端连接断开时，断开对应的mysql连接
    $con->onClose = function($con) use ($con_to_mysql) {
        $con_to_mysql->close();
    };
    // 客户端连接发生错误时，断开对应的mysql连接
    $con->onError = function($con) use ($con_to_mysql) {
        $con_to_mysql->close();
    };
};
// 运行worker
Worker::runAll();
// 执行 mysql -uhomestead -P600 -h127.0.0.1 -p 进行访问，可以正常访进入数据库
