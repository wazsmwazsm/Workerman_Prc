<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;

$worker = new Worker('http://0.0.0.0:600');
$worker->onConnect = function($con) {
    echo "new connection from address".
    $con->getRemoteIp().':'.$con->getRemotePort()."\n";
};

Worker::runAll();
