<?php

// 建议使用 workerman 的功能实现客户端
// 直接使用 socket 可能有粘包、拆包问题 ?
// 建议使用 JsonInt_client2.php 来进行数据获取
// 此例子只是使用 stream socket 进行一次 tcp 访问

$socket = stream_socket_client('tcp://127.0.0.1:600', $erron, $errstr);
// 连接错误
$socket or die($erron.$errstr);

// 发送数据
$msg = json_encode(['name'=>'jack', 'gen'=>'ma']);   // 构造包数据
$msg_pk_length = strlen($msg) + 4;                   // 获得包长
$data = pack('N', $msg_pk_length).$msg;              // 构成协议包
// 发送数据
fwrite($socket, $data);
// 获取相应数据
$response = fread($socket, 256);
// 关闭连接
fclose($socket);
$response_length = unpack('Ntotal_length', substr($response, 0, 4))['total_length'];
$response_data = substr($response, 4);

echo "返回数据包: 长度 : $response_length , 包体 : $response_data \n";
