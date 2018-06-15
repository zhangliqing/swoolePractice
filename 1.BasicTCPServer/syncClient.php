<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-15
 * Time: 下午2:19
 */

class syncClient{
    private $client;

    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
        $this->client->connect('127.0.0.1',9501,1);
    }

    public function connect(){
        $msg = rand(1,12);
        $this->client->send($msg);
        $message = $this->client->recv();
        echo "get message from server:{$message} \n";
        $this->client->close();
    }
}
$client = new syncClient();
$client->connect();