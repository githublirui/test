<?php
if(file_exists('../common.php')){
    include_once('../common.php');
}else{
    throw new Exception('common is not exists');
}

$users = $connection->selectCollection('users');
$bottle_categories = $connection->selectCollection('bottle_categories');
$bottle_actions = $connection->selectCollection('bottle_actions');

$data['user_from_id'] = new MongoId(trim($_POST['user_from_id']));
$data['bottle_catid'] = new MongoId(trim($_POST['bottle_catid']));
$data['content'] = trim(htmlspecialchars(addslashes($_POST['content'])));
$data['created_time'] = date('Y-m-d H:i:s');

$users_from_info = $users->findOne(array('_id' => new MongoId($data['user_from_id'])));
if(empty($users_from_info)){
    throw new Exception('user from is not exists');
}

$bottle_categories_info = $bottle_categories->findOne(array('_id' => new MongoId($data['bottle_catid'])));
if(empty($bottle_categories_info)){
    throw new Exception('bottle category is not exists');
}

function getUserIdTo($user_id){
    global $users;
    $users_count = $users->count();
    $rand = rand(0, $users_count - 1);
    $user_id_to_cursor = $users->find(array('_id' => array('$ne' => new MongoId($user_id))))->skip($rand)->limit(1);
    $user_id_to = $user_id_to_cursor->hasNext() ? $user_id_to_cursor->getNext() : array();
    if(empty($user_id_to)){
        return getUserIdTo($user_id);
    }else{
        return $user_id_to;
    }
}
$user_id_to_info = getUserIdTo($data['user_from_id']);
$data['user_id_to'] = new MongoId($user_id_to_info['_id']);

$last_insert_id = $bottle_actions->insert($data);
header('location: userinfo.php');