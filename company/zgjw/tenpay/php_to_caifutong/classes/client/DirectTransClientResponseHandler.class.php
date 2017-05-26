<?php

/**
 * 中间件接口应答类
 * ============================================================================
 * api说明：
 * getKey()/setKey(),获取/设置密钥
 * getContent() / setContent(), 获取/设置原始内容
 * getParameter()/setParameter(),获取/设置参数值，只能获取、设置第一级xml数据
 * getAllParameters(),获取所有参数
 * isTenpaySign(),是否财付通签名,true:是 false:否
 * getDebugInfo(),获取debug信息
 * 
 * ============================================================================
 *
 */
require_once ("ClientResponseHandler.class.php");

class DirectTransClientResponseHandler extends ClientResponseHandler {
	
}

?>