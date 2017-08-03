<?php

namespace Protocols;

/*

  自定义协议 : 以 { "firstName":"Bill" , "lastName":"Gates" } json 数据为数据格式，\n 字符为结尾的协议

*/


class JsonNL
{
    /**
     * 检查包的完整性
     * 如果能够得到包长，则返回包的在buffer中的长度，否则返回0继续等待数据
     * 如果协议有问题，则可以返回false，当前客户端连接会因此断开
     * @param string $buffer
     * @return int
     */
    public static function input($buffer)
    {
        // 获取字符 \n 的位置
        $pos = strpos($buffer, "\n");
        // 没有换行符，无法得知包长，返回0继续等待数据
        if($pos === false) {
          return 0;
        }
        // 有换行符，返回当前包长（包含换行符）
        return $pos + 1;
    }

    /**
     * 打包，当向客户端发送数据的时候会自动调用
     * @param array\object $buffer
     * @return string
     */
    public static function encode($buffer)
    {
        // json序列化，并加上换行符作为请求结束的标记
        return json_encode($buffer)."\n";
    }

    /**
     * 解包，当接收到的数据字节数等于 input 返回的值（大于0的值）自动调用
     * 并传递给onMessage回调函数的$data参数
     * @param string $json_str
     * @return array/object
     */
    public static function decode($json_str)
    {
        // 去掉换行，还原成数组
        return json_decode(trim($json_str), true);
    }

}
