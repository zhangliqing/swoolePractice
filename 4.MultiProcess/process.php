<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-19
 * Time: 上午11:28
 */



$workers = [];
for ($i = 0; $i < 2; $i++){
    $process = new swoole_process('callback_function',false);
    //三个参数：
    //$function：子进程创建成功后要执行的函数
    //$redirect_stdin_stdout：重定向子进程的标准输入和输出。 设置为true，则在进程内echo将不是打印屏幕，而是写入到管道，读取键盘输入将变为从管道中读取数据。 默认为false，阻塞读取。
    //$create_pipe：是否创建管道，启用$redirect_stdin_stdout后，此选项将忽略用户参数，强制为true 如果子进程内没有进程间通信，可以设置为false。

    $pid = $process->start();
    $workers[$pid] = $process;
}

//主进程向管道的读写
foreach ($workers as $pid => $process) {
    $process->write("hello worker [$pid]\n");
    echo "this is message read from worker: ".$process->read();
}

//子进程向管道的读写
function callback_function($worker){
    $recv = $worker->read();
    echo "this is message read from master: {$recv} \n";

    $worker->write("hello master ,this is pipe ".$worker->pipe.",my pid is ".$worker->pid."\n");
    $worker->exit(0);
}