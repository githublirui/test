<?php
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$connection = socket_connect($socket, 'localhost', 8888) or die("Could not connect\n");

$data = 'i love adele';
$length = strlen($data);
if(!socket_sendto($socket, $data, $length, 0, '127.0.0.1', 8888)){
    echo "can not send data to the destination host";
}

//read respose from socket
while($buffer = socket_read($socket, 1024)){
    echo 'Response: ',$buffer,"\n";
}
?>