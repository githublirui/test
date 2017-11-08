<?php

/**
 * 51talk签到
 */
//登录成功后获取数据 
function get_content($url, $cookie) {
    $headers = array(
        'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0',
        'Cookie' => 'global=6db0d315-d3be-481a-ba70-9ae92edf072b; PHPSESSID=b4po9h71comipknp7rs5oignv7; SpMLdaPx_uuid=5461567840; www_ugroup=4; user_ust=0jrIyK4H3SST3xP0TuE2fO8FZ0mbL3s9YSBAK4zgd0d5Z9SLMBPx2fJeL99l%2F34kofgNY7ZdOfB10y6Fxf%2BvVLL6qwDvGpU%3D; user_usg=MCwCFGcAVqlYJz%2FArGK%2BoIYICL1Jt%2BGtAhRcBQVFk3U5I%2BfK5wIBdJYm%2B5VQuQ%3D%3D; from_url=s.51talk.com%2Cs13.51talk.com; SpMLdaPx_pvid=1510035955731; j68x_2132_saltkey=k584c4D5; j68x_2132_lastvisit=1509676926; ASP.NET_SessionId=k0fnb22lrtav5wsxqcqwbkde; j68x_2132_creditnotice=0D1D0D0D0D0D0D0D0D2136; j68x_2132_creditbase=0D62D0D0D0D0D0D0D0; j68x_2132_creditrule=%E6%AF%8F%E5%A4%A9%E7%99%BB%E5%BD%95; servChkFlag=sso; 51talkpassuser=lirui; 51talkpass=5aw0MJceLVLilJ6DkG3SUofJsyln-DKab3kk*YbZEZgKQGHHkbyK7Q-d6DuqdoPNsXfMXbQrObo7lrbzBBNqUQ==; j68x_2132_sid=GLsG4a; j68x_2132_lip=172.16.231.100%2C1509682170; j68x_2132_lastact=1509688310%09uc.php%09; j68x_2132_auth=9792K7X9n3Aw9RlhDOGg8eQm0nRJ85a3xPfpNU4NI50MfTL8IcjLaauoFT%2FMSpsELSA4jeajjfj0Se7sn7Ohcu1B; _pk_ref.1.a8cd=%5B%22%22%2C%22%22%2C1510045498%2C%22http%3A%2F%2Fpassport.oa.51talk.com%2FDefault%2FIndex%22%5D; _pk_id.1.a8cd=6c301cf180735fa2.1510037715.2.1510045499.1510045498.; _pk_ses.1.a8cd=*; oa_anime_cookie=yes',
        'Content-Length' => 0,
        'Host' => 'web.oa.51talk.com',
        'Origin' => 'http://web.oa.51talk.com',
        'Referer' => 'http://web.oa.51talk.com/',
        'X-Requested-With' => 'XMLHttpRequest',
    );
    $params = [];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_POST, 1); //post方式提交 
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie 
    $rs = curl_exec($ch); //执行cURL抓取页面内容 
    curl_close($ch);
    return $rs;
}

function login_post($url, $cookie, $post) {
    $headers = array(
        'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0',
        'Cookie' => 'global=6db0d315-d3be-481a-ba70-9ae92edf072b; PHPSESSID=b4po9h71comipknp7rs5oignv7; SpMLdaPx_uuid=5461567840; www_ugroup=4; user_ust=0jrIyK4H3SST3xP0TuE2fO8FZ0mbL3s9YSBAK4zgd0d5Z9SLMBPx2fJeL99l%2F34kofgNY7ZdOfB10y6Fxf%2BvVLL6qwDvGpU%3D; user_usg=MCwCFGcAVqlYJz%2FArGK%2BoIYICL1Jt%2BGtAhRcBQVFk3U5I%2BfK5wIBdJYm%2B5VQuQ%3D%3D; from_url=s.51talk.com%2Cs13.51talk.com; SpMLdaPx_pvid=1510035955731; servChkFlag=sso; 51talkpass=; 51talkpassuser=',
        'Host' => 'passport.oa.51talk.com',
        'Origin' => 'http://passport.oa.51talk.com',
        'Referer' => 'http://passport.oa.51talk.com/default/logout?from_url=http://web.oa.51talk.com/&with_token=false',
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Upgrade-Insecure-Requests' => 1,
    );
    $curl = curl_init(); //初始化curl模块 
    curl_setopt($curl, CURLOPT_URL, $url); //登录提交的地址 
    curl_setopt($curl, CURLOPT_HEADER, 0); //是否显示头信息 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0); //是否自动显示返回的信息 
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中 
    curl_setopt($curl, CURLOPT_POST, 1); //post方式提交 
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post)); //要提交的信息 
    curl_exec($curl); //执行cURL 
    $info = curl_getinfo($curl);
    var_dump($info);
    die;
    curl_close($curl); //关闭cURL资源，并且释放系统资源 
}

$cookie = dirname(__FILE__) . '/cookie_51talk.txt';

//获取一个key
$url = "http://passport.oa.51talk.com/Default/Index";
$content = get_content($url, $cookie);
$pattern = '/\<input\s+name=\"key\"\s+type\=\"hidden\"\s+value\=\"(\w+)\"/is';
$pattern1 = '/\{key\:\"(\w+)\"/is';

preg_match($pattern, $content, $match);
preg_match($pattern1, $content, $match1);

$key = $match[1];
$redirKey = $match1[1];

//pwd = (new Xxtea('b398cb18c9e9419498f420b223001633')).xxtea_encrypt('649037629@qq.com');
//1pTTNpM5LDfsxEmEkEr+JCicXNDlbBY2lMYoCw==
// 
//R509i23G/yQyvJ5wl1ugEMY+q70BtY2a9HpmoQ==
//echo decrypt($v, 'huqinlou'); //解密
//设置post的数据 
$realPwd = "649037629@qq.com"; //真实密码
$pwd = "1pTTNpM5LDfsxEmEkEr+JCicXNDlbBY2lMYoCw==";
$key = 'b398cb18c9e9419498f420b223001633';

$post = array(
    'userName' => 'lirui',
    'pwd' => $realPwd,
    'password' => $pwd,
    'from_url' => 'http://web.oa.51talk.com/',
    'with_token' => 'false',
    'appkey' => '',
    'key' => $key,
);

//登录地址 
$url = "http://passport.oa.51talk.com/Default/Index";
//设置cookie保存路径 
//登录后要获取信息的地址 
$url2 = "http://web.oa.51talk.com/Attendance/Record";
//模拟登录 
login_post($url, $cookie, $post);

//获取登录页的信息 
$content = get_content($url2, $cookie);
var_dump($content);
die;
//删除cookie文件 
@ unlink($cookie);
//匹配页面信息 
$preg = "/<td class='portrait'>(.*)<\/td>/i";
preg_match_all($preg, $content, $arr);
$str = $arr[1][0];
//输出内容 
echo $str;
?>
