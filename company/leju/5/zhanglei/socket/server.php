<?php
$host = '127.0.0.1';
$port = 8888;
set_time_limit(0);

/**
 * @description 产生一个socket, 返回资源类型
 * @params
 *     AF_INET 产生socket协议, 用ipv4传输
 *     SOCK_STREAM 使用TCP协议传输, 基于字节流的连接
 *     SOL_TCP 基于tcp建立socket, 可靠
 */
if(!($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) die("Could not create socket\r\n");

/**
 * @description 绑定到端口上
 * @params
 *     $socket socket连接资源
 *     $host 主机地址
 *     $post 主机地址下socket的端口
 */ 
if(!socket_bind($socket, $host, $port)) die("Could not bind to port\r\n");

/**
 * @description 监听socket(等待从客户端传送过来的连接)
 * @params $socket socket的资源
 * @params 允许最多等待连接的队列数
 */
if(!socket_listen($socket, 3)) die("Could not set listen the socket\r\n");

/**
 * @desciption 产生一个唯一接受后的连接 返回唯一连接后新的socket连接
 * @params
 *     $socket socket资源
 */
while($accept_socket = socket_accept($socket)){
    /**
     * @description特定的socket中读取指定的字节, 返回读取的字符串
     * @$params
     *     socket连接
     *     基于TCP连接的最大字节数
     */
    $input = socket_read($accept_socket, 1024);

    $output = sprintf('read sucess the data is %s %s', $input, "\r\n");
    
    // 获取远程主机ip地址以及端口
    socket_getpeername($accept_socket, $client_ip, $client_port);

    echo $client_ip . "\r\n" . $input;
    /**
     * @description 写入到socket去
     * @params
     *     $new_socket socket连接
     *     $output 写入的新字符串
     *     $length 写入的新字符串的长度
     */
    socket_write($accept_socket, $output, strlen($output));
}

// 关闭两个socket连接
socket_close($accept_socket);
socket_close($socket);
?>
