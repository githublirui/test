<?php

/**
 * 返回script内容后台应答类
 * ============================================================================
 * api说明：
 * getKey()/setKey(),获取/设置密钥
 * getContent() / setContent(), 获取/设置原始内容
 * getParameter()/setParameter(),获取/设置参数值
 * getAllParameters(),获取所有参数
 * isTenpaySign(),是否财付通签名,true:是 false:否
 * getDebugInfo(),获取debug信息
 * 
 * ============================================================================
 *
 */

require_once ("ClientResponseHandler.class.php");

class ScriptClientResponseHandler extends ClientResponseHandler  {
	
	function __construct() {
		$this->ScriptClientResponseHandler();
	}
	
	function ScriptClientResponseHandler() {
		parent::ClientResponseHandler();
	}
	
	//设置原始内容
	function setContent($content) {
		$this->content = $content;
		
		$ret = preg_match ("/window.location.href[ \t]*=[ \t]*[\'\"](.*)[\'\"]/i", $this->content, $arr);
		if($ret ) {
			$url = explode("?", $arr[1]);
			if($url && count($url) >= 2) {
				$paras = explode("&", $url[1]);
				foreach($paras as $para) {
					$kav = explode("=", $para);
					$this->setParameter($kav[0], urldecode($kav[1]));
				}
			} else {
				$this->setParameter("pay_result", "99");
				$this->setParameter("pay_info", "返回包格式错误，请检查协议是否改变！");
			}
			
		} else {
			$this->setParameter("pay_result", "99");
			$this->setParameter("pay_info", "返回包格式错误，请检查协议是否改变！");
		}

	}
	
}


?>