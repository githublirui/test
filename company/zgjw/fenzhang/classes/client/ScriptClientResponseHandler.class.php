<?php

/**
 * ����script���ݺ�̨Ӧ����
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

require_once ("ClientResponseHandler.class.php");

class ScriptClientResponseHandler extends ClientResponseHandler  {
	
	function __construct() {
		$this->ScriptClientResponseHandler();
	}
	
	function ScriptClientResponseHandler() {
		parent::ClientResponseHandler();
	}
	
	//����ԭʼ����
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
				$this->setParameter("pay_info", "���ذ���ʽ��������Э���Ƿ�ı䣡");
			}
			
		} else {
			$this->setParameter("pay_result", "99");
			$this->setParameter("pay_info", "���ذ���ʽ��������Э���Ƿ�ı䣡");
		}

	}
	
}


?>