<?php

/**
 * ��̨����������
 * ============================================================================
 * api˵����
 * init(),��ʼ��������Ĭ�ϸ�һЩ������ֵ����cmdno,date�ȡ�
 * getGateURL()/setGateURL(),��ȡ/������ڵ�ַ,����������ֵ
 * getKey()/setKey(),��ȡ/������Կ
 * getParameter()/setParameter(),��ȡ/���ò���ֵ,ֻ�ܻ�ȡ�����õ�һ��xml����
 * setAllParameterFromXml��ֱ��ͨ��xml��ʽ�������ò���
 * getAllParameters(),��ȡ���в���
 * getRequestURL(),��ȡ������������URL
 * doSend(),�ض��򵽲Ƹ�֧ͨ��
 * getDebugInfo(),��ȡdebug��Ϣ
 * 
 * ============================================================================
 *
 */

 /* ��һ��Ŀ¼ */
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
	
	//��ȡxml����
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
				//���ӽڵ�
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