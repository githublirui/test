<?php

/**
 * 后台调用请求类
 * ============================================================================
 * api说明：
 * init(),初始化函数，默认给一些参数赋值，如cmdno,date等。
 * getGateURL()/setGateURL(),获取/设置入口地址,不包含参数值
 * getKey()/setKey(),获取/设置密钥
 * getParameter()/setParameter(),获取/设置参数值,只能获取、设置第一级xml数据
 * setAllParameterFromXml，直接通过xml格式内容设置参数
 * getAllParameters(),获取所有参数
 * getRequestURL(),获取带参数的请求URL
 * doSend(),重定向到财付通支付
 * getDebugInfo(),获取debug信息
 * 
 * ============================================================================
 *
 */

 /* 上一级目录 */
$classes_path = str_replace('\\', '/', dirname(dirname(__FILE__)));
require_once ($classes_path . "/RequestHandler.class.php");

class DirectTransClientRequestHandler extends RequestHandler {

	function __construct() {
		$this->DirectTransClientRequestHandler();
	}
	
	function DirectTransClientRequestHandler() {
		parent::RequestHandler();
		$this->setGateURL("https://mch.tenpay.com/cgi-bin/mchbatchtransfer.cgi");
	}
	
	//获取xml编码
	function getXmlEncode($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}
	
	function setAllParameterFromXml($xmlStr) {
		$xml = simplexml_load_string($xmlStr);
		$encode = $this->getXmlEncode($xmlStr);
		
		if($xml && $xml->children()) {	
			foreach ($xml->children() as $node) {
				//有子节点
				if($node->children()) {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
					
				} else {
					$k = $node->getName();
					$v = (string)$node;
				}
				
				if($encode!="" && $encode != "UTF-8") {
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				
				$this->setParameter($k, $v);		
			}
		}
		
	}
	
	function getRequestURL() {
	
		$xml = "<?xml version=\"1.0\" encoding=\"GB2312\" ?><root>";
		
		foreach($this->parameters as $k => $v) {
			$xml .= "<" . $k . ">" . $v . "</" . $k . ">";
		}
		
		$xml .= "</root>";
		
	
		$content = base64_encode($xml);
		
		
		$md5Res1 = strtolower(md5($content));
		$md5Src2 = $md5Res1 . $this->key;
	
		$abstract = strtolower(md5($md5Src2));
		
		$this->_setDebugInfo($xml . "=>" . $content . "=>" . $md5Src2 . " => sign:" . $abstract);
		
		$requestURL = $this->getGateURL() . "?" . "content=" . urlencode($content) . "&abstract=" . urlencode($abstract);		
		
		return $requestURL;
	}
}

?>