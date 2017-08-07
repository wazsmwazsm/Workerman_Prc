<?php

namespace Protocols;

/*

协议定义

  首部 4 字节网络字节序unsigned int，标记整个包的长度
  数据部分为 Json 字符串

数据包样本

  ****{"type":"message","content":"hello all"}

其中首部四字节 *号代表一个网络字节序的unsigned int数据，为二进制字符，紧接着是Json的数据格式的包体数据

*/

class JsonInt
{
    public static function input($buffer)
    {
        // 接收到的数据还不够4字节，无法得知包的长度，返回0继续等待数据
        if(strlen($buffer) < 4 ) {
            return 0;
        }
        // 利用 unpack 函数将首部 4 字节转换成数字，首部 4 字节即为整个数据包长度
        // unpack N 会取一个 32 位整形数，也就是 4 个字节
        // total_length 代表取得后的元素的键值
        $total_length = unpack('Ntotal_length', substr($buffer, 0, 4))['total_length'];

        return $total_length;
    }

    public static function encode($data)
    {
        // 编码
        $body_json_str = json_encode($data);
        // 计算整个包的长度，首部4字节+包体字节数
        $total_length = 4 + strlen($body_json_str);
        // 返回打包数据 N -- 无符号长整数 (32位, 大端字节序)
        return pack('N', $total_length).$body_json_str;
    }

    public static function decode($json_string)
    {
        // 去掉首部4字节，得到包体Json数据
        $body_json_str = substr($json_string, 4);
        // json解码
        return json_decode($body_json_str, true);
    }
}
