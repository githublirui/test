<?php
$test_order = "
    create table test_order_%u(
        id int unsigned not null auto_increment comment '订单自增长ID',
        uid int unsigned not null comment '用户ID',
        money int unsigned not null comment '订单的钱数',
        ctime timestamp not null default current_timestamp comment '订单生成时间',
        primary key(id)
    )engine MyISAM default charset=utf8
";

$test_order_0 = sprintf($test_order, 0);
$test_order_1 = sprintf($test_order, 1);

$test_user = "
    create table test_user(
        uid int unsigned not null auto_increment comment '用户自增长ID',
        username varchar(30) not null comment '用户名',
        primary key(uid)
    )engine MyISAM default charset=utf8
";

$test_stat = "
    create table test_stat(
        id int unsigned not null auto_increment comment '统计自增长ID',
        uid int unsigned not null comment '用户ID',
        money int unsigned not null comment '用户总钱数',
        primary key(id)
    )engine MyISAM default charset=utf8
";

$link = mysql_connect('localhost', 'root', 'root');
mysql_select_db('test', $link);
mysql_query('set names utf8');

$drop_table = "drop table if exists %s";

$d_test_order_0 = sprintf($drop_table, "test_order_0");
$d_test_order_1 = sprintf($drop_table, "test_order_1");
$d_test_user = sprintf($drop_table, "test_user");
$d_test_stat = sprintf($drop_table, "test_stat");

mysql_query($d_test_order_0);
mysql_query($d_test_order_1);
mysql_query($d_test_user);
mysql_query($d_test_stat);


$test_order_0_message = mysql_query($test_order_0);
$test_order_1_message = mysql_query($test_order_1);
$test_user_message = mysql_query($test_user);
$test_stat_message = mysql_query($test_stat);

$message = "create %s table failed";
if(!$test_order_0_message){
    echo  sprintf($message, "test_order_0");
}else if(!$test_order_1_message){
    echo  sprintf($message, "test_order_1");
}else if(!$test_user_message){
    echo  sprintf($message, "test_user");
}else if(!$test_stat_message){
    echo  sprintf($message, "test_stat");
}else{
    echo "create tables success";
}
?>