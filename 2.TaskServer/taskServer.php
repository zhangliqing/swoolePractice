<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-15
 * Time: 下午4:27
 */
class taskServer{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server('127.0.0.1',9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'deamonize' => false,
            'max_request' => 1000,
            'task_worker_num' => 8
        ));

        $this->serv->on('Start',array($this,'onStart'));
        $this->serv->on('Connect',array($this,'onConnect'));
        $this->serv->on('Receive',array($this,'onReceive'));
        $this->serv->on('Close',array($this,'onClose'));
        $this->serv->on('Task',array($this,'onTask'));
        $this->serv->on('Finish',array($this,'onFinish'));

        $this->serv->start();
    }

    public function onStart($serv){
        echo "swoole server Start\n";
    }

    public function onConnect($serv,$fd){
        echo $fd." Client Connect. \n";
    }

    public function onReceive($serv,$fd,$from_id,$data){
        echo "get message from client {$fd} : {$data}\n";
        $param = array(
            'fd' => $fd
        );
        $serv->task(json_encode($param));
        echo "continue handle worker \n";
    }

    public function onClose($serv,$fd){
        echo "client closed \n";
    }

    public function onTask($serv, $task_id, $from_id, $data){
        echo "task {$task_id} from worker {$from_id} \n";
        echo "Data: {$data}\n";
        for ($i = 0; $i < 10; $i++){
            sleep(1);
            echo "task {$task_id} handle {$i} times...";
        }
        $fd = json_decode($data,true);
        $serv->send($fd['fd'],"data in task {$task_id}");
        return "task {$task_id}'s result \n'";
    }

    public function onFinish($serv, $task_id, $data){
        echo "task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }
}

$server = new taskServer();