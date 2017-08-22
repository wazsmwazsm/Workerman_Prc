<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use GlobalData\Client;

/****  实例化一个\GlobalData\Client服务 ****/

// 连接Global Data服务端
$global = new Client('127.0.0.1:2207');

// 触发 $global->__isset('somedata') 查询服务端是否存储了 key 为 somedata 的值
isset($global->somedata);
// 触发 $global->__set('somedata',array(1,2,3))，通知服务端存储 somedata 对应的值为 array(1,2,3)
$global->somedata = [1, 2, 3];

// 触发 $global->__get('somedata')，从服务端查询 somedata 对应的值
var_export($global->somedata);

// 触发 $global->__unset('somedata'),通知服务端删掉 somedata 及对应的值
unset($global->somedata);
var_export($global->somedata);
