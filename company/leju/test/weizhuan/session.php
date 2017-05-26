<?php
@session_start();
//退出登录
if($_GET['do']=='exit'){
	unset($_SESSION['login']);
	unset($_SESSION['userdata']);
	header("location:login.php",301);
	exit;
}
if($_SESSION['login']!==1){
	header("location:login.php",301);
	exit;
}else{
	$session=$_SESSION['userdata'];
	$row_user=$mysql->query("select * from `userdata` where `id` in({$_SESSION['userdata']['id']})");
	$userdata=$row_user[0];
}

//print_r($session);
// if($_POST){
	// echo "<script>alert('测试账号权限不足，无法修改内容，请联系QQ297003558');window.history.go(-1)</script>";
	// exit;
// }
// if(is_array($_GET)&&count($_GET)>0){
	// echo "<script>alert('测试账号权限不足，无法修改内容，请联系QQ297003558');window.history.go(-1)</script>";
	// exit;
// }