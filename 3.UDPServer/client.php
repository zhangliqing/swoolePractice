<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-19
 * Time: 上午10:23
 */

$client = new swoole_client(SWOOLE_SOCK_UDP);
$client->connect('127.0.0.1', 9503, 1);
$i = 0;

while ($i < 10){
    $client->send($i."\n");
    $message = $client->recv();
    echo "this is from server: {$message} \n";
    $i++;
}