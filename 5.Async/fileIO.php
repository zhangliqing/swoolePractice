<?php
/**
 * Created by PhpStorm.
 * User: lanny
 * Date: 18-6-19
 * Time: 下午2:25
 */

//swoole_async_readfile会将文件内容全部复制到内存,最大可读取4M的文件，受限于SW_AIO_MAX_FILESIZE宏
swoole_async_readFile("./1.txt",function ($fileName,$content){
    var_dump($fileName,$content);
});
echo "done \n";

//Swoole\Async::writeFile(string $filename, string $fileContent, callable $callback = null, int $flags = 0)
//参数1为文件的名称，必须有可写权限，文件不存在会自动创建。打开文件失败会立即返回false
//参数2为要写入到文件的内容，最大可写入4M
//参数3为写入成功后的回调函数，可选
//参数4为写入的选项，可以使用FILE_APPEND表示追加到文件末尾
//如果文件已存在，底层会覆盖旧的文件内容
swoole_async_writefile('./test.log', "123456", function($filename) {
    echo "wirte ok.\n";
}, $flags = FILE_APPEND);


//bool swoole_async_read(string $filename, mixed $callback, int $size = 8192, int $offset = 0);
//分段读取，可以用于读取超大文件。每次只读$size个字节，不会占用太多内存。

swoole_async_read("./2.txt", function($fileName, $content){
    var_dump($fileName, strlen($content));
}, 8196, 0);
echo "done" .PHP_EOL;

//swoole_async_write是分段写的。不需要一次性将要写的内容放到内存里，所以只占用少量内存
//当offset为-1时表示追加写入到文件的末尾
$content = file_get_contents('./2.txt');
swoole_async::write("./test.log", $content, -1, function($fileName){
    var_dump($fileName);
});
echo "done" .PHP_EOL;
