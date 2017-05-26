<?php
@session_start();
//退出登录
if($_GET['do']=='exit'){
	unset($_SESSION['admin']);
	unset($_SESSION['admindata']);
	header("location:login.php",301);
	exit;
}
if(!isset($_SESSION['admin'])){
	//_location('login.php',301);
	header("location:login.php",301);
	exit;
}
// if($_SESSION['admindata']['username']=='admin'){
	// if($_POST){
	// echo "<script>alert('测试账号权限不足，无法修改内容');window.history.go(-1)</script>";
	// exit;
// }
	// if(is_array($_GET)&&count($_GET)>0){
		// echo "<script>alert('测试账号权限不足，无法修改内容');window.history.go(-1)</script>";
		// exit;
	// }
// }