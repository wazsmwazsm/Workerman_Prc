<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use GlobalData\Client;

/*
  bool \GlobalData\Client::cas(string $key, mixed $old_value, mixed $new_value)

  原子替换，用$new_value替换$old_value。
  仅在当前客户端最后一次取值后，该key对应的值没有被其他客户端修改的情况下， 才能够将值写入。

  替换成功返回true，否则返回false。

  原子操作的原理说明:

    多进程同时操作同一个共享变量时，有时候要考虑并发问题。

    例如A B两个进程同时给用户列表添加一个成员。

    A B进程当前用户列表都为$global->user_list = array(1,2,3)。

    A进程操作$global->user_list变量，添加一个用户4。

    B进程操作$global->user_list变量，增加一个用户5。

    A进程设置变量$global->user_list = array(1,2,3,4)成功。

    B进程设置变量$global->user_list = array(1,2,3,5)成功。

    此时B进程设置的变量将A进程设置的变量覆盖，导致数据丢失。

    以上由于读取和设置不是一个原子操作，导致并发问题。

    要解决这种并发问题，可以使用cas原子替换接口。

    cas接口在改变一个值之前，

    会根据$old_value判断这个值是否被其它进程更改过，

    如果有更改，则不替换，返回false。否则替换返回true。

*/

$global = new Client('127.0.0.1:2207');

// 初始化列表
$global->user_list = [1, 2, 3];
// 向 user_list 添加一个值, 如果有多个进程占用，循环读取等待直到可写
do {
   $old_value = $new_value = $global->user_list;
   $new_value[] = 4;
} while( ! $global->cas('user_list', $old_value, $new_value));

var_dump($global->user_list);
