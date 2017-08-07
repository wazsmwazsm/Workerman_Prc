<?php
/** 上传文件客户端 **/
// 上传地址
$address = "127.0.0.1:600";
// 检查上传文件路径参数
// 检查上传文件路径参数
isset($argv[1]) or die("use php client.php \$file_path\n");

// 上传文件路径
$file_to_transfer = trim($argv[1]);
// 上传的文件本地不存在
is_file($file_to_transfer) or die("$file_to_transfer not exist\n");

// 建立 socket 连接
$client = stream_socket_client($address, $errno, $errmsg) or die($erron.$errstr);

// 设置阻塞
stream_set_blocking($client, TRUE);
// 文件名、文件名长度、文件二进制数据
$file_name = basename($file_to_transfer);
$file_data = file_get_contents($file_to_transfer);
$file_data = base64_encode($file_data); // base64编码

// 协议包构造
// 协议头长度 4字节包长+1字节文件名长度
// 数据包
$package_data = [
    'file_name' => $file_name,
    'file_data' => $file_data,
];
$package = json_encode($package_data)."\n";

// 执行上传
fwrite($client, $package);
// 打印结果
echo fread($client, 8192),"\n";
