<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Workerman\Worker;

/*

  设置当前Worker实例以哪个用户运行。此属性只有当前用户为root时才能生效。不设置时默认以当前用户运行。

  建议$user设置权限较低的用户，例如www-data、apache、nobody等

*/
$worker = new Worker('text://0.0.0.0:600');
$worker->user = 'www-data';   // 注意是当前 worker 的运行用户，不是此脚本的运行用户
$worker->onWorkerStart = function($worker)
{
    echo "Worker starting... \n";
};

// 启动 worker
Worker::runAll();
