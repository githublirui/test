<?php

//include_once ('360_safe3.php');
//*****************************************************
#���ݿ�����
$dbhost = "192.168.2.88";
$dbuser = 'root';
$dbpw = '';
$dbname = 'mytest';
$pconnect = 0;
$dbcharset = 'utf8';
$tablepre = '';

#cookie��������
// $cookiepre = 'uix_';              // cookie ǰ׺ 
//$cookiedomain = '.zgjwzh.local';    // cookie ������
$main_domain = 'http://106.3.78.224:5000/';         //������
$session_expire_time = 7 * 60 * 60 * 24;         //session����ʱ��
ini_set('session.cookie_domain', $cookiedomain); //����session������
$cookiepath = '/';   // cookie ����·��
#������������
$dev = true; #���忪��ģʽ 
//��̨��¼����ip
$allow_ips = array(
//    '127.0.0.1',
//    '106.3.78.220',
//    '192.168.2.1 - 192.168.2.255',
//    '192.168.1.1 - 192.168.1.255',
//    '106.3.78.224 - 106.3.78.231',
);

#������ʼ��
if ($dev) {
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", 0);
    error_reporting(0);
}


