<?php
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/html; charset=utf-8');
//require('360.php');
require('webscan.php');
$dbname = '���ݿ���';
$host = 'localhost';
$port = '3306';
$user = '�û�';
$pwd = '����';
require('mysql_class.php');
$config = include('config.php');
$mysql = new MySQL($host,$user,$pwd,$dbname,$port);
$site=$config['site'];
$admin=$config['admin'];//��̨����
$pp=$config['pp'];//����ip�۸�
$ti=$config['ti'];//������ֽ��
$song=$config['song'];;//ע�����ͽ��
$song1='';//����ע�����ͽ��
$p1=$config['p1'];//������ɷֳ�