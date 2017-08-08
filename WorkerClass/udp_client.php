<?php

$socket = stream_socket_client('udp://127.0.0.1:600', $erron, $errstr);
// 连接错误
$socket or die($erron.$errstr);

isset($argv[1]) or die("use php client.php name \n");

// 发送数据
$msg = trim($argv[1]);

// 发送数据
fwrite($socket, $msg);
// 获取相应数据
$response = fread($socket, 256);
// 关闭连接
fclose($socket);

echo $response;
