<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-19
 * Time: 上午10:08
 */

$serv = new swoole_server("127.0.0.1",9503, SWOOLE_BASE, SWOOLE_SOCK_UDP);
$serv->set([
    'worker_num' => 4,
    'task_worker_num' => 4,
    'deamonize' => false
]);

$serv->on('Packet', function ($serv, $data, $clientInfo){
    $serv->sendto($clientInfo['address'],$clientInfo['port'],"Server".$data);

    //余下的工作交给task执行
    $serv->task($data);
});

$serv->on('Task', function ($serv, $task_id, $from_id, $data){
    echo "this task {$task_id} from worker {$from_id} \n";

    //模拟耗时操作
    for ($i = 0; $i < 2; $i++){
        sleep(1);
        echo "task {$task_id} handling…… \n";
    }

    //将处理结果返回给onfinish函数
    return "task {$task_id}'s result'";
});

$serv->on('Finish', function ($serv, $task_id, $data){
    echo "task {$task_id} finish,result is: {$data}  \n";
});

$serv->start();