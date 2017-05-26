<meta charset='utf-8' />
<?php
if(file_exists('../common.php')){
    include_once('../common.php');
}else{
    throw new Exception('common is not exists');
}

if(empty($_COOKIE['user_id'])){
    header('location: login.php');
}

// 发送漂流瓶的用户
$users = $connection->selectCollection('users');
$user_info = $users->findOne(array('_id' => new MongoId($_COOKIE['user_id'])));

// 漂流瓶类型列表
$bottle_categories = $connection->selectCollection('bottle_categories');
$bottle_categories_cursor = $bottle_categories->find();
while($bottle_categories_cursor->hasNext()){
    $bottle_categories_lists[] = $bottle_categories_cursor->getNext();
}

// 收到漂流瓶
$bottle_actions = $connection->selectCollection('bottle_actions');
$bottle_unread_count = $bottle_actions->count(array('user_id_to' => new MongoId($_COOKIE['user_id']), 'is_read' => false));
$bottle_read_count = $bottle_actions->count(array('user_id_to' => new MongoId($_COOKIE['user_id'])));
?>
<style>
a {
    text-decoration: none;
}
</style>
姓名： <?php echo isset($user_info['username']) ? $user_info['username'] : ''; ?><br />
邮箱： <?php echo isset($user_info['email']) ? $user_info['email'] : ''; ?><br />
性别： <?php echo isset($user_info['sex']) ? $user_info['sex'] : ''; ?><br />
时间： <?php echo isset($user_info['created_time']) ? $user_info['created_time'] : ''; ?><br /><br />

<?php if(!empty($bottle_categories_lists)): ?>
<form action='bottle_todo.php' method='post'>
    <input name='user_from_id' type='hidden' value="<?php echo $_COOKIE['user_id']; ?>" />
    漂流瓶：&nbsp;&nbsp;&nbsp;
    <select name='bottle_catid'>
        <?php foreach($bottle_categories_lists as $bottle): ?>
        <option value='<?php echo isset($bottle['_id']) ? $bottle['_id'] : 0; ?>'>
            <?php echo isset($bottle['name']) ? $bottle['name'] : ''; ?>
        </option>
        <?php endforeach; ?>
    </select>
    <br /><br />
    漂流瓶内容：<textarea cols='40' rows='7' name='content'></textarea>
    <br /><br />
    <input name='submit' value='提交' type='submit' />
</form>
<?php endif; ?>

<div>
    你有<a href="bottle_view.php?type=0"> <?php echo $bottle_unread_count; ?> </a>未读， 
    <a href="bottle_view.php?type=1"> <?php echo $bottle_read_count; ?> </a>已读
</div>