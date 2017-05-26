<?php
if(file_exists('../common.php')){
    include_once('../common.php');
}else{
    throw new Exception('common is not exists');
}
$users = $connection->selectCollection('users');

$current_users = $users->findOne(array('_id' => new MongoId($_COOKIE['user_id'])));
if(!$current_users){
    throw new Exception('you have no permission to view this page');
}

$bottle_categories = $connection->selectCollection('bottle_categories');

$data['name'] = htmlspecialchars(addslashes(trim($_POST['name'])));
$data['description'] = htmlspecialchars(addslashes(trim($_POST['description'])));

$result = $bottle_categories->insert($data);
if($result){
    header('location: admin.php');
}else{
    header('location: bottle_add.php');
}