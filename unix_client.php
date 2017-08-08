<?php

// 用 sudo 来运行此程序

$socket = stream_socket_client('unix:///tmp/my.sock', $erron, $errstr);
// 连接错误
$socket or die($erron.$errstr);

$file_data = file_get_contents('./composer.json');
// 发送数据
fwrite($socket, $file_data);
// 获取相应数据
$response = fread($socket, 1024);
// 关闭连接
fclose($socket);
var_dump($response);
