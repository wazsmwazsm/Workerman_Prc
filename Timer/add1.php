<?php

/*

add
  int \Workerman\Lib\Timer::add(float $time_interval, callable $callback [,$args = array(), bool $persistent = true])
  定时执行某个函数或者类方法。

  注意：定时器是在当前进程中运行的，workerman中不会创建新的进程或者线程去运行定时器。

  参数

    time_interval
    多长时间执行一次，单位秒，支持小数，可以精确到0.001，即精确到毫秒级别。

  callback

    回调函数注意：如果回调函数是类的方法，则方法必须是public属性

  args

    回调函数的参数，必须为数组，数组元素为参数值

  persistent

    是否是持久的，如果只想定时执行一次，则传递false（只执行一次的任务在执行完毕后会自动销毁，
    不必调用Timer::del()）。默认是true，即一直定时执行。

  返回值

    返回一个整数，代表计时器的timerid，可以通过调用Timer::del($timerid)销毁这个计时器。

del
  boolean \Workerman\Lib\Timer::del(int $timer_id)
  删除某个定时器

  参数

    timer_id
    定时器的id，即add接口返回的整型

  返回值

    boolean



注意事项

  定时器使用注意事项
    1、只能在onXXXX回调中添加定时器。全局的定时器推荐在onWorkerStart回调中设置，
    针对某个连接的定时器推荐在onConnect中设置。

    2、添加的定时任务在当前进程执行(不会启动新的进程或者线程)，如果任务很重（特别是涉及到网络IO的任务）
    可能会导致该进程阻塞，暂时无法处理其它业务。所以最好将耗时的任务放到单独的进程运行，例如建立一个/多个Worker进程运行

    3、当前进程忙于其它业务时或者当一个任务没有在预期的时间运行完，这时又到了下一个运行周期，
    则会等待当前任务完成才会运行，这会导致定时器没有按照预期时间间隔运行。也就是说当前进程的业务都是串行执行的，
    如果是多进程则进程间的任务运行是并行的。

    4、需要注意多进程设置了定时任务造可能成并发问题


*/
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Workerman\Worker;
use \Workerman\Lib\Timer;

$worker = new Worker();
// 开启多少进程则开多少定时任务，注意业务逻辑
$worker->count = 5;
$worker->onWorkerStart = function($worker) {
    // 每 2.5 s 执行一次
    Timer::add(2.5, function() {
        echo "task run\n";
    });
};

Worker::runAll();
