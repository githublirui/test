<?php

//---------------------------------------------------------
//财付通分帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require ("classes/ResponseHandler.class.php");

/* 密钥 */
$key = "82d2f8b9fd7695aec51415ab2900a755";

/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//支付结果、状态码
$pay_result = $resHandler->getParameter("pay_result");

//支付信息、错误信息
$pay_info = $resHandler->getParameter("pay_info");

//判断签名
if($resHandler->isTenpaySign()) {
	
	//交易单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	
	if( "0" == $pay_result ) {
	
		//------------------------------
		//处理业务开始
		//------------------------------
		
		//注意交易单不要重复处理
		//注意判断返回金额
		
		//------------------------------
		//处理业务完毕
		//------------------------------	
		
		//调用doShow, 打印meta值跟js代码,告诉财付通处理成功,并在用户浏览器显示$show页面.
		$show = "http://localhost/tenpay_split/show.php";
		$resHandler->doShow($show);
	
	} else {
		//当做不成功处理
		echo "<br/>" . "支付失败" . "<br/>";
	}
	
} else {
	echo "<br/>" . "认证签名失败" . "<br/>";
	
	echo "<br/>" . "状态码：" . $pay_result . "<br/>";
	
	echo "<br/>" . "错误信息：" . $pay_info . "<br/>";	
}

//获取debug信息,建议把debug信息写入日志，方便定位问题
//echo $resHandler->getDebugInfo();

?>
