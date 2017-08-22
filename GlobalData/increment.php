<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use GlobalData\Client;

/*
  bool \GlobalData\Client::increment(string $key[, int $step = 1])

  原子增加。将一个数值元素增加参数 step 指定的大小。
  如果元素的值不是数值类型，将其作为 0 再做增加处理。如果元素不存在返回false。
*/

$global = new Client('127.0.0.1:2207');

$global->some_key = 0;

// 非原子增加, 可能会导致多进程之间数据不一
$global->some_key++;

echo $global->some_key."\n";

// 原子增加
$global->increment('some_key');

echo $global->some_key."\n";
