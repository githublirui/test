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

$users_lists_cursor = $users->find();
while($users_lists_cursor->hasNext()){
    $users_lists[] = $users_lists_cursor->getNext();
}

$bottle_categories = $connection->selectCollection('bottle_categories');
$bottle_categories_lists_cursor = $bottle_categories->find();
while($bottle_categories_lists_cursor->hasNext()){
    $bottle_categories_lists[] = $bottle_categories_lists_cursor->getNext();
}

include_once('users_lists.php');
include_once('bottle_categories_lists.php');
