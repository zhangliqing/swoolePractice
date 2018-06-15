<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-15
 * Time: 上午11:44
 */

class Server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server('127.0.0.1',9501);

        $this->serv->set([
            'worker_num' => 10,
            'deamonize' => true,
        ]);

        $this->serv->addlistener("::1",9500,SWOOLE_SOCK_TCP);


        //
        $this->serv->on('Start',array($this,'onStart'));
        $this->serv->on('Connect',array($this,'onConnect'));
        $this->serv->on('Receive',array($this,'onReceive'));
        $this->serv->on('Close',array($this,'onClose'));
        $this->serv->on('ManagerStart',function (swoole_server $server){
           echo "On manager start \n";
        });
        $this->serv->on('WorkerStart',function ($serv, $workerId){
            echo $workerId."---Worker Start \n" ;
        });
        $this->serv->on('WorkerStop',function ($serv, $workerId){
            echo $workerId."---Worker Stop \n" ;
        });

        $this->serv->start();
    }

    public function onStart($serv){
        echo "server start \n";
    }

    public function onConnect($serv, $fd, $from_id){
        $serv->send($fd,"hello {$fd}");
        echo "send hello {$fd} to client {$fd}";
    }

    public function onReceive($serv, $fd, $from_id, $data){
        echo "get message from client {$fd}:{$data}\n";
        $serv->send($fd, $data);
    }

    public function onClose($serv, $fd, $from_id){
        echo "client {$fd} close connection \n";
    }
}

$server = new Server();