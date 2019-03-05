<?php
/**
 * Created by PhpStorm.
 * User: v_hrrchen
 * Date: 2019/3/4
 * Time: 11:12
 */
require_once "./ext/String.php";
//确保在连接客户端时不会超时
set_time_limit(0);
//设置IP和端口号
$address = "127.0.0.1";
$port = 8080;
if(isset($argv[1])) {
    $address = $argv[0];
}
if(isset($argv[2])) {
    $port = $argv[1];
}

/**
 * 创建一个SOCKET
 * AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6
 * SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM
 */
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() fail:" . socket_strerror(socket_last_error()) . "/n");
//阻塞模式
socket_set_block($sock) or die("socket_set_block() fail:" . socket_strerror(socket_last_error()) . "/n");
//绑定到socket端口
$result = socket_bind($sock, $address, $port) or die("socket_bind() fail:" . socket_strerror(socket_last_error()) . "/n");
//开始监听
$result = socket_listen($sock, 4) or die("socket_listen() fail:" . socket_strerror(socket_last_error()) . "/n");
echo "OK\nBinding the socket on $address:$port ... ";
echo "OK\nNow ready to accept connections.\nListening on the socket ... \n";

$StringOperateObj = new StringOperate();

do { // never stop the daemon
    //它接收连接请求并调用一个子连接Socket来处理客户端和服务器间的信息
    $msgsock = socket_accept($sock) or  die("socket_accept() failed: reason: " . socket_strerror(socket_last_error()) . "/n");
    while(1){
        //读取客户端数据
        echo "Read client data \n";
        //socket_read函数会一直读取客户端数据,直到遇见\n,\t或者\0字符.PHP脚本把这写字符看做是输入的结束符.
        $buf = socket_read($msgsock, 8192);
        var_dump($buf);

        //$result = $StringOperateObj->run($buf);
        //var_dump($result);

        echo "Received msg: $buf   \n";

        if($buf == "bye"){
            //接收到结束消息，关闭连接，等待下一个连接
            socket_close($msgsock);
            continue;
        }

        //数据传送 向客户端写入返回结果
        //$msg = json_encode($result);
        $msg = "hello client";
        socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
    }

} while (true);
socket_close($sock);
