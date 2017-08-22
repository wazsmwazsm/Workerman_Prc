<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use GlobalData\Client;

/*

  GlobalData组件无法共享资源类型的数据，例如mysql连接、socket连接等无法共享。

  如果在Workerman环境中使用GlobalData/Client，请在onXXX回调中实例化GlobalData/Client对象，
  例如在onWorkerStart中实例化。

*/

$global = new Client('127.0.0.1:2207');

echo $global->somedata;

var_export(isset($global->abc));

$global->abc = array(1,2,3);

var_export($global->abc);

unset($global->abc);

var_export($global->add('abc', 10));

var_export($global->increment('abc', 2));

var_export($global->cas('abc', 12, 18));
