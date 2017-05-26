<?php
if(file_exists('../common.php')){
    include_once('../common.php');
}else{
    throw new Exception('common is not exists');
}

$user_id_to = trim($_COOKIE['user_id']);
$type = intval($_GET['type']);

$users = $connection->selectCollection('users');
$bottle_actions = $connection->selectCollection('bottle_actions');

$users_info = $users->findOne(array('_id' => new MongoId($user_id_to)));
if(empty($users_info)){
    header('location: login.php');
}

$cond = array(
    'user_id_to' => new MongoId($user_id_to),
    'is_read'    => !empty($type) ? 1 : array('$exists' => false) 
);

$bottle_actions_lists_cursor = $bottle_actions->find($cond);
while($bottle_actions_lists_cursor->hasNext()){
    $bottle_actions_lists[] = $bottle_actions_lists_cursor->getNext();
}
?>
<meta charset='utf-8' />
<style>
table td {
    padding-left: 10px;
}
</style>
<div>hi <span style='color: red'><?php echo $users_info['username']; ?></span></div>

<?php if(!empty($bottle_actions_lists)): ?>
<div style='font-weight: bold; margin-top: 10px'><?php echo !empty($type) ? '已读' : '未读'; ?>消息列表</div>
<table style='border-collapse: collapse; width: 800px; margin-top: 10px;' border=1>
    <tr>
        <td>发送人</td>
        <td>内容</td>
        <td>时间</td>
    </tr>
    <?php foreach($bottle_actions_lists as $list): ?>
    <?php $user_id_to = $bottle_actions->getDBRef(array('$ref' => 'users', '$id' => $list['user_from_id'])); ?>
    <tr>
        <td><?php echo $user_id_to['username']; ?></td>
        <td><?php echo isset($list['content']) ? $list['content'] : ''; ?></td>
        <td><?php echo isset($list['created_time']) ? $list['created_time'] : ''; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
