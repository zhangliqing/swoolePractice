<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-15
 * Time: 下午5:09
 */

class asyncClient{
    private $client;

    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);
        $this->client->on('Connect', array($this,'onConnect'));
        $this->client->on('Receive', array($this,'onReceive'));
        $this->client->on('Close', array($this,'onClose'));
        $this->client->on('Error', array($this,'onError'));
    }

    public function connect(){
        if(!$fp = $this->client->connect("127.0.0.1",9501,1)){
            echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
            return;
        }
    }

    public function onConnect($cli){
        fwrite(STDOUT,"Enter Msg:");
        swoole_event_add(STDIN, function (){
            fwrite(STDOUT,"Enter Msg:");
            $msg = trim(fgets(STDIN));
            $this->send($msg);
        });
    }

    public function onClose($cli){
        echo "client closed \n";
    }

    public function onError(){

    }

    public function onReceive($cli,$data){
        echo "Received: ".$data."\n";
    }

    public function send($data){
        $this->client->send($data);
    }

    public function isConnected($cli){
        return $this->client->isConnected();
    }
}

$client = new asyncClient();
$client->connect();