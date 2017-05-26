<meta charset='utf-8' />
<?php

session_start();

$conf = array(
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'root',
    'name' => 'demo'
);
$link = mysql_connect($conf['host'] . ":" . $conf['port'], $conf['user'], $conf['pass']);
mysql_select_db($conf['name'], $link);
mysql_query("set names utf8", $link);

function getUser($username){
    $sql = sprintf("select * from user where username = '%s' limit 1", mysql_real_escape_string($username));
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    return $row;
}

function redirect($url, $msg = false){
    echo "<script>";
    if($msg) echo sprintf("alert('%s');", $msg);
    echo sprintf("location.href='%s'", $url);
    echo "</script>";
}

$act = null;
if(isset($_GET['act'])) $act = $_GET['act'];

if($act == 'nonetoken'){

    // session里面不加token
    $_POST = array_map('htmlspecialchars', array_map('addslashes', array_map('trim', $_POST)));
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if(empty($username)) die('please input your username');
    if(empty($password)) die('please input your password');
    $row = getUser($username);
    if(!$row) die('your username is error');
    if($row['password'] != md5($password)) die('your password is error');

    // 登录成功, 将uid以及username放入session中, 并且设置1小时过期
    $_SESSION['current_user']['uid'] = $row['id'];
    $_SESSION['current_user']['username'] = $row['username'];
    setcookie('uid', $_SESSION['current_user']['uid'], time() + 3600, '/');
    setcookie('username', $_SESSION['current_user']['username'], time() + 3600, '/');

    redirect('login.php?act=info', '登录成功');

}elseif($act == 'token'){

    /*
     * session里面加入token
     * cookie根据浏览器而定, 换了浏览器状态都没了
     * TOKEN值可以将http_user_agent作为参数, 加密, 放入session中, 然后判断
     */
    $_POST = array_map('htmlspecialchars', array_map('addslashes', array_map('trim', $_POST)));
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if(empty($username)) die('please input your username');
    if(empty($password)) die('please input your password');

    $row = getUser($username);
    if(!$row) die('your username is error');

    // 登录成功, 将uid username 放入session中, 并设置过期时间, 将token设置, 做安全设置
    $_SESSION['current_user']['uid'] = $row['id'];
    $_SESSION['current_user']['username'] = $row['username'];
    $token = $_SERVER['HTTP_USER_AGENT'] . md5(session_name()) . md5($_COOKIE[session_name()]);
    $_SESSION['current_user']['token'] = md5($token);

    redirect('login.php?act=tokeninfo', '登录成功');

}elseif($act == 'tokeninfo'){

    // 用session验证用户登录信息
    if(empty($_SESSION['current_user']['uid']) || empty($_SESSION['current_user']['username']))
        redirect('login.php', '请登录');

    $row = getUser($_SESSION['current_user']['username']);
    if(empty($row) || $row['id'] != $_SESSION['current_user']['uid'])
        redirect('login.php', '用户错误');

    $chk_token = $_SERVER['HTTP_USER_AGENT'] . md5(session_name()) . md5($_COOKIE[session_name()]);
    if($_SESSION['current_user']['token'] != md5($chk_token))
        redirect('login.php', 'token不正确, 请不要当黑客');

    echo "欢迎回来, 使用token认证, " . $_SESSION['current_user']['username'];

}elseif($act == 'info'){

    // 用cookie做验证

    if(empty($_COOKIE['uid']) || empty($_COOKIE['username'])){
        redirect('login.php', '请登录');
    }
    $current_user = getUser($_COOKIE['username']);
    
    /* 如果仅仅验证cookie里面的信息是不安全的, 因为cookie可以伪造. 
     * 由于session.name没有失效, 则通过session.id的值还能够找到服务器端对应的session
     * 所以要验证cookie里面的值跟session的值相等, 则客户端与服务器端的值相等, 则证明有效
     */
    if(($_COOKIE['uid'] != $_SESSION['current_user']['uid']) || ($_COOKIE['username'] != $_SESSION['current_user']['username'])) 
        redirect('login.php', 'session与cookie的uid不相等, cookie可能被伪造');
    
    if(!$current_user) redirect('login.php', '登录的用户不存在');
    //print_r($_SESSION['current_user']['username']);
    echo "欢迎你, 你好, " . $_COOKIE['username'];

}elseif($act == 'session_info'){

    // 用session去验证, 除非设置setcookie(session_name(), session_id(), time() + 3600), 否则在此流程控制里面过期时间有php设置的过期时间控制

    /**
     * 如果服务器端用session去做验证用户的登录状态
     * 如果得到某一个合法用户的session.id的值, 通过http发送cookie, 则能得到该合法用户的状态
     * 直接将session.id放入到cookie中无效, 从http头部得到的cookie的session.id去服务器找对应的session
     * http://localhost/github/session/login.php?act=session_info&PHPSESSIDSHIRLEY=qjvnqj36rj60u2vtfnivfum7g7
     */
    
    /* 无效 */
    if($_GET[session_name()]){
        $session_id = $_GET[session_name()];
    }
    $_COOKIE[session_name()] = $session_id;
    /* 无效 */
    
    header("Set-Cookie: PHPSESSIDSHIRLEY=qjvnqj36rj60u2vtfnivfum7g7");

    if(empty($_SESSION['current_user']['uid']) || empty($_SESSION['current_user']['username']))
        redirect('login.php', '请登录');

    // 用户浏览器版本不一样, 就算得到用户的session.id 也不能通过验证
    /*
    $chk_token = $_SERVER['HTTP_USER_AGENT'] . md5(session_name()) . md5($_COOKIE[session_name()]);
    if($_SESSION['current_user']['token'] != md5($chk_token))
        redirect('login.php', 'token不正确, 请不要当黑客');
    */

    echo "欢迎你, 你好, " . $_SESSION['current_user']['username'];

}elseif($act == 'statusbysess'){

    /* 
     * 通过得到session.id的值窃取用户状态
     * 先自己合法登录, 然后将session.id的值放入url中, 让别的合法用户点击, 保证自己的session.id不过期
     * 服务器判断到有此session.id后, php内核不会新建新的session.id
     * 接着用户登录, session.id对应的session变成了这个合法用户的session
     * 然后刷新页面, 取得合法用户的状态
     * http://localhost/github/session/login.php?PHPSESSIDSHIRLEY=qjvnqj36rj60u2vtfnivfum7g7
     */
    
    // 经证实, 简单的从url传递session.id. 浏览器会重新生成新的session.id
    if(empty($_SESSION['current_user']['uid']) || empty($_SESSION['current_user']['username']))
        redirect('login.php', '请登录');

    echo "欢迎你, 你好, " . $_SESSION['current_user']['username'];

}elseif($act == 'tokenlogin'){
?>
<form method='post' action='?act=token'>
    username: <input name='username' type='text' /><br /><br />
    password: <input name='password' type='password' /><br /><br />
    <input name='submit' value='登录' type='submit' />
</form>
<?php
}else{
?>
<form method='post' action='?act=nonetoken'>
    username: <input name='username' type='text' /><br /><br />
    password: <input name='password' type='password' /><br /><br />
    <input name='submit' value='登录' type='submit' />
</form>
<?php
}
?>