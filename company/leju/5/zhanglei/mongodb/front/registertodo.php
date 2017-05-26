<?php
if(file_exists('../common.php')){
    include_once('../common.php');
}else{
    throw new Exception('common php file is not exists');
}

$users = $connection->selectCollection('users');

// 注册
$data['username'] = addslashes(htmlspecialchars(trim($_POST['username'])));
$data['password'] = md5(addslashes(htmlspecialchars(trim($_POST['password']))));
$data['email'] = addslashes(htmlspecialchars(trim($_POST['email'])));
$data['sex'] = trim($_POST['sex']);
$data['created_time'] = date('Y-m-d H:i:s');

if(!$data['username']){
    die('请填写用户名');
}
if(!$data['password']){
    die('请填写密码');
}
if(!$data['email']){
    die('请填写邮箱');
}
if(!$data['sex'] || !in_array($data['sex'], array('man', 'woman'))){
    die('请选择用户性别');
}

$result = $users->insert($data);
if($result){
	setcookie('user_id', $data['_id'], time() + 3600, '/');
	setcookie('username', $data['username'], time() + 3600, '/');
    header('location: userinfo.php');
}
