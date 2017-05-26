<?php

/**
 * ��̨Ӧ����
 * ============================================================================
 * api˵����
 * getKey()/setKey(),��ȡ/������Կ
 * getContent() / setContent(), ��ȡ/����ԭʼ����
 * getParameter()/setParameter(),��ȡ/���ò���ֵ
 * getAllParameters(),��ȡ���в���
 * isTenpaySign(),�Ƿ�Ƹ�ͨǩ��,true:�� false:��
 * getDebugInfo(),��ȡdebug��Ϣ
 * 
 * ============================================================================
 *
 */

class ClientResponseHandler  {
	
	/** ��Կ */
	var $key;
	
	/** Ӧ��Ĳ��� */
	var $parameters;
	
	/** debug��Ϣ */
	var $debugInfo;
	
	//ԭʼ����
	var $content;
	
	function __construct() {
		$this->ClientResponseHandler();
	}
	
	function ClientResponseHandler() {
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		$this->content = "";
	}
		
	/**
	*��ȡ��Կ
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*������Կ
	*/	
	function setKey($key) {
		$this->key = $key;
	}
	
	//����ԭʼ����
	function setContent($content) {
		$this->content = $content;
		
		$xml = simplexml_load_string($this->content);
		$encode = $this->getXmlEncode($this->content);
		
		if($xml && $xml->children()) {
			foreach ($xml->children() as $node){
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
	
	//��ȡԭʼ����
	function getContent() {
		return $this->content;
	}
	
	/**
	*��ȡ����ֵ
	*/	
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*���ò���ֵ
	*/	
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*��ȡ��������Ĳ���
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}	
	
	/**
	*�Ƿ�Ƹ�ͨǩ��,������:����������a-z����,������ֵ�Ĳ������μ�ǩ����
	*true:��
	*false:��
	*/	
	function isTenpaySign() {
		
		$srcRes = $this->content;
		
		//��������ȥ��<sign>XXXXX</sign>,������MD5,�ѽ��ת���ɴ�д
		$start = strpos($srcRes,"<sign>");
		$end = strpos($srcRes,"</sign>");
		$revStr = substr($srcRes,$start,$end+7-$start);
		
		$tempSign = strtoupper(md5(str_replace($revStr,"",$srcRes)));
		
    //����KEY֮���ټ���һ��MD5���ѽ��ת���ɴ�д,�γ����յ�ǩ��
		$tempSign .= "&key=" . $this->getKey();		
		$sign = strtoupper(md5($tempSign));
		
		$tenpaySign = $this->getParameter("sign");
				
		//debug��Ϣ
		$this->_setDebugInfo($tempSign . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
	
	/**
	*��ȡdebug��Ϣ
	*/	
	function getDebugInfo() {
		return $this->debugInfo;
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
	
	/**
	*����debug��Ϣ
	*/	
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}
	
	/**
	 * �Ƿ�Ƹ�ͨǩ��
	 * @param signParameterArray ǩ���Ĳ�������
	 * @return boolean
	 */	
	function _isTenpaySign($signParameterArray) {
	
		$signPars = "";
		foreach($signParameterArray as $k) {
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}			
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug��Ϣ
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;		
		
	
	}
	
}


?>