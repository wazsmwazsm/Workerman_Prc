<?php

// 请使用 workerman 的功能实现客户端
// 直接使用 socket 可能有粘包、拆包问题
// 请使用 JsonInt_client2.php 来进行数据获取
// 此例子只是使用 stream socket 进行一次 tcp 访问

$socket = stream_socket_client('tcp://127.0.0.1:600', $erron, $errstr);
// 连接错误
$socket or die($erron.$errstr);

// 发送数据
$msg = json_encode(['name'=>'jack', 'gen'=>'ma']);
$data = pack('N', strlen($msg)).$msg;

fwrite($socket, $data);

$response = fread($socket, 256);

var_dump($response);

fclose($socket);
$response_length = unpack('Ntotal_length', substr($response, 0, 4))['total_length'];
$response_data = substr($response, 4);
var_dump($response_length, $response_data);
