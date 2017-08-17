<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;

/*
pipe
  说明:
    void Connection::pipe(TcpConnection $target_connection)

  参数
    将当前连接的数据流导入到目标连接。内置了流量控制。此方法做 TCP 代理非常有用
*/
