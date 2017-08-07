<?php

namespace Protocols;

/*

协议定义

  json+换行，json中包含了文件名以及 base64_encode 编码（会增大 1/3 的体积）的文件数据

数据包样本

  {"file_name":"logo.png","file_data":"PD9waHAKLyo…"}\n

  注意末尾为一个换行符，在 PHP 中用双引号字符 "\n" 标识

*/

class TextTransfer
{
    public static function input($buffer)
    {
        // 获取包长度
        $len = strlen($buffer);
        if($buffer[$len - 1] !== "\n") {
            return 0;
        }
        return $len;
    }

    public static function encode($data)
    {
        // 可以根据自己的需要编码发送给客户端的数据，这里只是当做文本原样返回
        return $data;
    }

    public static function decode($json_string)
    {
        // 解包
        $package_data = json_decode(trim($json_string), TRUE);
        // 取出文件名
        $file_name = $package_data['file_name'];
        // 取出base64_encode后的文件数据
        $file_data = $package_data['file_data'];
        // 还原二进制数据
        $file_data = base64_decode($file_data);

        // 返回数据
        return [
            'file_name' => $file_name,
            'file_data' => $file_data,
        ];
    }
}
