<?php
$link = mysql_connect('localhost', 'root', 'root');
mysql_select_db('test', $link);
mysql_query('set names utf8');

$act = isset($_GET['act']) ? $_GET['act'] : false;
if($act == 'adduser'){
    $string = "我是科比我是第一%u";
    for($i=1; $i<=20; $i++){
        $username = sprintf($string, $i);
        $sql = sprintf("insert into test_user set username = '%s'", $username);
        mysql_query($sql);
    }
}elseif($act == 'paybyproc'){
    $uid = $_POST['uid'];
    $money = $_POST['paycount'];
    $proc = sprintf("call stat_procedure(%u, %u)", $uid, $money);
    mysql_query($proc);
}elseif($act == 'paybytrigger'){
    $uid = $_POST['uid'];
    $money = $_POST['paycount'];
    $sql = "insert into test_order_0 set uid = $uid, money = $money";
    mysql_query($sql);
}else{
    $sql = "select * from test_user";
    $result = mysql_query($sql);
    $list = array();
    while($row = mysql_fetch_assoc($result)){
        $list[] = $row;
    }
?>
<meta charset='utf-8' />
<table border=1>
    <tr>
        <th>用户ID</th>
        <th>用户名</th>
        <th>给他充值</th>
    </tr>
    <?php if($list): ?>
    <?php foreach($list as $key => $user): ?>
        <tr>
            <td><?php echo $user['uid']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <th>
                <?php if(($key%2) == 0): ?>
                <form method='post' action='?act=paybytrigger'>
                <?php else: ?>
                <form method='post' action='?act=paybyproc'>
                <?php endif; ?>
                    <input name='paycount' />
                    <input name='uid' type='hidden' value='<?php echo $user['uid']; ?>' />
                    <?php if(($key%2) == 0): ?>
                    <input name='submit' value='触发器充值' style='color:red; font-weight:bolder' type='submit' />
                    <?php else: ?>
                    <input name='submit' value='存储过程充值' style='color:blue; font-weight:bolder' type='submit' />
                    <?php endif; ?>
                </form>
            </th>
        </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</table>
<?php
}
?>