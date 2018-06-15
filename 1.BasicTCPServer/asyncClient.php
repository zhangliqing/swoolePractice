<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-15
 * Time: 下午3:17
 */

$client = new swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);   //通过传参指明创建的是一个异步客户端。
$client->on("connect", function ($cli){   //$client->connect() 发起连接的操作会立即返回，不存在任何等待。当对应的IO事件完成后，swoole底层会自动调用设置好的回调函数。
    var_dump($cli->isConnected());
    var_dump($cli->getsockname());
    var_dump(($cli->sock));

    $i = 0;
    while ($i < 100){
        $cli->send($i."\n");
        $i++;
    }
});

$client->on("receive",function ($cli,$data){
    echo "receive $data";
});

$client->on("error",function ($cli){
    echo "error ".$cli-errCode."\n";
});

$client->on("close", function ($cli){
    echo "connect closed. \n";
});

$client->connect('127.0.0.1',9501);