<?php
session_start();
require('conn.php');
require('functions.php');
//echo $session['phone'];
//print_r($_COOKIE);
if(time()-$_COOKIE['yzm_time']<=60){
print <<<alert
	<script>
	alert('1分钟内只能获取一次验证码，请不要重复操作');
	</script>
alert;
exit;
}else{
	setcookie('yzm',$rand,time()+60);
	setcookie('yzm_time',time(),time()+60);
print <<<alert
	<script>
	alert('验证码已发送到您手机，5分钟内有效');
	</script>
alert;
	
}
//print_r($userdata);

//通用接口发送样例
// $apikey = "5b4aa85957d6b5ce942ac4c9e8e343fe"; //请用自己的apikey代替
// $mobile = guolv($_GET['phone']); //请用自己的手机号代替
// $yzm1 = substr($mobile,3,4);
// $text="您的验证码是{$yzm1}";
// echo send_sms($apikey,$text,$mobile);

//模板接口样例（不推荐。需要测试请将注释去掉。)
//以下代码块已被注释
//5b4aa85957d6b5ce942ac4c9e8e343fe
  $apikey = $config['yunpian']; //请用自己的apikey代替
  $mobile = guolv($_GET['phone']); //请用自己的手机号代替
  $yzm1 = substr($mobile,3,4);
  $tpl_id = 1; //对应默认模板 【#company#】您的验证码是#code#
  $tpl_value = "#company#={$config['sitename']}&#code#={$yzm1}";
  echo tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);


/**
* 通用接口发短信
* apikey 为云片分配的apikey
* text 为短信内容
* mobile 为接受短信的手机号
*/
function send_sms($apikey, $text, $mobile){
	$url="http://yunpian.com/v1/sms/send.json";
	$encoded_text = urlencode("$text");
	$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
	return sock_post($url, $post_string);
}

/**
* 模板接口发短信
* apikey 为云片分配的apikey
* tpl_id 为模板id
* tpl_value 为模板值
* mobile 为接受短信的手机号
*/
function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
	$url="http://yunpian.com/v1/sms/tpl_send.json";
	$encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
	$post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
	return sock_post($url, $post_string);
}

/**
* url 为服务的url地址
* query 为请求串
*/
function sock_post($url,$query){
	$data = "";
	$info=parse_url($url);
	$fp=fsockopen($info["host"],80,$errno,$errstr,30);
	if(!$fp){
		return $data;
	}
	$head="POST ".$info['path']." HTTP/1.0\r\n";
	$head.="Host: ".$info['host']."\r\n";
	$head.="Referer: http://".$info['host'].$info['path']."\r\n";
	$head.="Content-type: application/x-www-form-urlencoded\r\n";
	$head.="Content-Length: ".strlen(trim($query))."\r\n";
	$head.="\r\n";
	$head.=trim($query);
	$write=fputs($fp,$head);
	$header = "";
	while ($str = trim(fgets($fp,4096))) {
		$header.=$str;
	}
	while (!feof($fp)) {
		$data .= fgets($fp,4096);
	}
	return $data;
}
?>