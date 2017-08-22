<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use GlobalData\Client;

/*
  bool \GlobalData\Client::add(string $key, mixed $value)

  原子添加。如果 key 已经存在，会返回 false.
*/

$global = new Client('127.0.0.1:2207');

if($global->add('some_key', 10)) {
    echo $global->some_key."add success \n";
} else {
    echo "add fail, ".$global->some_key." already exist\n";
}
