<?php
// 未输入参数
isset($argv[1]) or die("Please enter message !\n");

$msg = json_encode(['name'=>'jack', 'gender'=>'male']);

$socket = stream_socket_client('tcp://127.0.0.1:600', $erron, $errstr);
// 连接错误
$socket or die($erron.$errstr);

// 发送数据
$data = pack('N', strlen($msg)).$msg;

fwrite($socket, $data, strlen($data) - 1);

$response = fread($socket, 1024);

fclose($socket);
$response_length = unpack('Ntotal_length', substr($response, 0, 4))['total_length'];
$response_data = substr($response, 4);
var_dump($response_length, $response_data);
