<?php
//---------------------------------------------------------
//财付通财付通建立委托退款关系请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("classes/PayRequestHandler.class.php");

/* 商户号 */
$bargainor_id = "1900000107";

//请求的URL
$reqUrl = "https://www.tenpay.com/cgi-bin/trust/showtrust_refund.cgi?spid=" . $bargainor_id;


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>财付通建立委托退款关系演示</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">建立委托退款关系</a>
</body>
</html>
