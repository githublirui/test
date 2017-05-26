<?php

//$bb = function() {
//    echo 333;
//    die;
//};
//$bb();
//匿名函数
//var_dump(sys_get_temp_dir());//返回用于临时文件的目录
$s = crc32('444abc1');
var_dump($s);
die;

$pid = pcntl_fork();
if ($pid == -1) {
    exit("fork error");
}
if ($pid == 0) {
    //子进程执行pcntl_fork的时候，pid总是0，并且不会再fork出新的进程
    echo "child process{$pid}\n";
} else {
    //父进程fork之后，返回的就是子进程的pid号，pid不为0
    echo "parent process{$pid}\n";
}
?>
