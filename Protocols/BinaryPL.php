<?php

namespace Protocols;

/*

协议定义

  struct
  {
    unsigned int total_len;  // 整个包的长度，大端网络字节序
    char         name_len;   // 文件名的长度
    char         name[name_len]; // 文件名
    char         file[total_len - BinaryTransfer::PACKAGE_HEAD_LEN - name_len]; // 文件数据
  }

数据包样本

  *****logo.png******************

其中首部四字节*号代表一个网络字节序的unsigned int数据，为不可见字符，
第5个*是用一个字节存储文件名长度，紧接着是文件名，接着是原始的二进制文件数据

*/

class BinaryPL
{

    // 协议头长度
    const PACKAGE_HEAD_LEN = 5;

    public static function input($buffer)
    {
        // 协议头长度判断
        if(strlen($buffer) < self::PACKAGE_HEAD_LEN) {
          return 0;
        }
        // 解包
        $package_data = unpack('Ntotal_len/Cname_len', $buffer);
        // 返回包长
        return $package_data['total_len'];
    }

    public static function encode($data)
    {
        // 可以根据自己的需要编码发送给客户端的数据，这里只是当做文本原样返回
        return $data;
    }

    public static function decode($buffer)
    {
        // 解包
        $package_data = unpack('Ntotal_len/Cname_len', $buffer);
        // 文件名长度
        $name_len = $package_data['name_len'];
        // 取出数据流中的文件名
        $file_name = substr($buffer, self::PACKAGE_HEAD_LEN, $name_len);
        // 取出文件二进制数据
        $file_data = substr($buffer, self::PACKAGE_HEAD_LEN + $name_len);

        return [
            'file_name' => $file_name,
            'file_data' => $file_data,
        ];

    }
}
