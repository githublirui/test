<?php
webscan_error ();

/**
 * 防护提示页
 */
function webscan_pape() {
	$pape = <<<HTML
<span style="display:none"><img src="http://img.users.51.la/5062132.asp" style="border:none" /></span>	
  <html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1" />
<title>安全防御</title>
<style type="text/css">
 body {
color: #000000;
background:#efefe9;
margin: 2px auto;
padding: 1px 1px 0px 1px;
font-size: .9em;
padding: 0px;
font-family: Arial, Verdana,Geneva,Helvetica,sans-serif;
-moz-border-radius:4px;-webkit-border-radius:4px;
max-width: 560px;
}
.title {
color: #fff;
background-color: red;
background-repeat: no-repeat;
background-position: right 50%; text-align:left; padding:3px; 
font-weight: bold; margin-left:0px;
margin-right:0px; margin-top:5px; margin-bottom:0px; }

.title a {
color: #FFFFFF;
 }
.list {
background:#fff;
background-color: #fffff;
color: #000000;
padding: 3px;
width:100%-4px;
margin-left:auto;
margin-right:auto;
font-weight: normal;
border-left:1px solid #d2d2d2;
border-right:1px solid #d2d2d2;
border-bottom:1px solid #d2d2d2;
}
</style>
</head>
  <body>
  <div class="title">安全提醒：<br /></div><div class="list">你输入的数据含有非法参数！qbvlap<br /></div><div class="list"><a href='javascript:history.go(-1)'>返回上页</a></div>
<div style="display:none">
<script language="javascript" type="text/javascript" src="http://js.users.51.la/5062132.js"></script>
</div>
  
  </body>
  </html>
HTML;
	echo $pape;
}
// 拦截开关(1为开启，0关闭)
$webscan_switch = 1;
// 提交方式拦截(1开启拦截,0关闭拦截,post,get,cookie,referre选择需要拦截的方式)
$webscan_post = 1;
$webscan_get = 1;
$webscan_cookie = 1;
$webscan_referre = 1;
// 后台白名单,后台操作将不会拦截,添加"|"隔开白名单目录下面默认是网址带 admin /dede/ 放行
$webscan_white_directory = 'ad';
// url白名单,可以自定义添加url白名单,默认是对phpcms的后台url放行
// 写法：比如phpcms 后台操作url index.php?m=admin
$webscan_white_url = array (
		//'index.php' => 'm=admin' 
);
// get拦截规则
$getfilter = "\\<.+javascript:window\\[.{1}\\\\x|<.*=(&#\\d+?;?)+?>|<.*(data|src)=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\\(\d+?|sleep\s*?\\([\d\.]+?\\)|load_file\s*?\\()|<[a-z]+?\\b[^>]*?\\bon([a-z]{4,})\s*?=|^\\+\\/v(8|9)|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT(\\(.+\\)|\\s+?.+?)|UPDATE(\\(.+\\)|\\s+?.+?)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?)FROM(\\(.+\\)|\\s+?.+?)|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
// post拦截规则
$postfilter = "<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\\(\d+?|sleep\s*?\\([\d\.]+?\\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT(\\(.+\\)|\\s+?.+?)|UPDATE(\\(.+\\)|\\s+?.+?)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?)FROM(\\(.+\\)|\\s+?.+?)|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
// cookie拦截规则
$cookiefilter = "benchmark\s*?\\(\d+?|sleep\s*?\\([\d\.]+?\\)|load_file\s*?\\(|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT(\\(.+\\)|\\s+?.+?)|UPDATE(\\(.+\\)|\\s+?.+?)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?)FROM(\\(.+\\)|\\s+?.+?)|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
// referer获取
$webscan_referer = empty ( $_SERVER ['HTTP_REFERER'] ) ? array () : array (
		'HTTP_REFERER' => $_SERVER ['HTTP_REFERER'] 
);

/**
 * 关闭用户错误提示
 */
function webscan_error() {
	if (ini_get ( 'display_errors' )) {
		ini_set ( 'display_errors', '0' );
	}
}

/**
 * 参数拆分
 */
function webscan_arr_foreach($arr) {
	static $str;
	if (! is_array ( $arr )) {
		return $arr;
	}
	foreach ( $arr as $key => $val ) {
		
		if (is_array ( $val )) {
			
			webscan_arr_foreach ( $val );
		} else {
			
			$str [] = $val;
		}
	}
	return implode ( $str );
}

/**
 * 攻击检查拦截
 */
function webscan_StopAttack($StrFiltKey, $StrFiltValue, $ArrFiltReq, $method) {
	$StrFiltValue = webscan_arr_foreach ( $StrFiltValue );
	if (preg_match ( "/" . $ArrFiltReq . "/is", $StrFiltValue ) == 1) {
		exit ( webscan_pape () );
	}
	if (preg_match ( "/" . $ArrFiltReq . "/is", $StrFiltKey ) == 1) {
		exit ( webscan_pape () );
	}
}
/**
 * 拦截目录白名单
 */
function webscan_white($webscan_white_name, $webscan_white_url = array()) {
	$url_path = $_SERVER ['PHP_SELF'];
	$url_var = $_SERVER ['QUERY_STRING'];
	if (preg_match ( "/" . $webscan_white_name . "/is", $url_path ) == 1) {
		return false;
	}
	foreach ( $webscan_white_url as $key => $value ) {
		if (! empty ( $url_var ) && ! empty ( $value )) {
			if (stristr ( $url_path, $key ) && stristr ( $url_var, $value )) {
				return false;
			}
		} elseif (empty ( $url_var ) && empty ( $value )) {
			if (stristr ( $url_path, $key )) {
				return false;
			}
		}
	}
	
	return true;
}

if ($webscan_switch && webscan_white ( $webscan_white_directory, $webscan_white_url )) {
	if ($webscan_get) {
		foreach ( $_GET as $key => $value ) {
			webscan_StopAttack ( $key, $value, $getfilter, "GET" );
		}
	}
	if ($webscan_post) {
		foreach ( $_POST as $key => $value ) {
			webscan_StopAttack ( $key, $value, $postfilter, "POST" );
		}
	}
	if ($webscan_cookie) {
		foreach ( $_COOKIE as $key => $value ) {
			webscan_StopAttack ( $key, $value, $cookiefilter, "COOKIE" );
		}
	}
	if ($webscan_referre) {
		foreach ( $webscan_referer as $key => $value ) {
			webscan_StopAttack ( $key, $value, $postfilter, "REFERRER" );
		}
	}
}

?>