<?php
if(file_exists('../common.php')){
    include_once('../common.php');
}else{
    throw new Exception('common php is not exists');
}

$users = $connection->selectCollection('users');

// 登录
$username = htmlspecialchars(addslashes(trim($_POST['username'])));
$password = htmlspecialchars(addslashes(trim($_POST['password'])));
$current = $users->findOne(array('username' => $username));
if(!$current){
	throw new Exception('用户名不存在');
}
if(md5($password) == $current['password']){
	setcookie('user_id', $current['_id'], time() + 3600, '/');
	setcookie('username', $current['username'], time() + 3600, '/');
	header('location: userinfo.php');
}else{
	throw new Exception('密码不正确');
}