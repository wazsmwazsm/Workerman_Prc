<?php

$socket = stream_socket_client('tcp://127.0.0.1:7895', $erron, $errstr);
// 连接错误
$socket or die($erron.$errstr);

// 构造推送数据
$data = ['uid' => 'uid0', 'percent' => '88%'];

fwrite($socket, json_encode($data)."\n");
echo fread($socket, 10);
