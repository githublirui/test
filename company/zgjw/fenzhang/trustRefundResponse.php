<?php

//---------------------------------------------------------
//财付通委托退款关系变化应答（处理回调）示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("./classes/TrustRefundResponseHandler.class.php");

/* 密钥 */
$key = "82d2f8b9fd7695aec51415ab2900a755";

/* 创建支付应答对象 */
$resHandler = new TrustRefundResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) {
	//财付通账号
	$uin = $resHandler->getParameter("uin");
	//状态
	$status = $resHandler->getParameter("status");
	

	if( "1" == $status ) {
		//处理添加关系逻辑
		echo "<br/>" . "签约成功" . "<br/>";
	} else if( "2" == $status ) {
		//处理撤销关系逻辑
		echo "<br/>" . "已撤销签约" . "<br/>";
	}
	
	
} else {
	echo "<br/>" . "认证签名失败" . "<br/>";
}

//获取debug信息,建议debug信息写入日志，方便定位问题
//echo $resHandler->getDebugInfo();

?>