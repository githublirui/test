<?php

require ("RequestHandler.class.php");
class BaseSplitRequestHandler extends RequestHandler {

	function __construct() {
		$this->BaseSplitRequestHandler();
	}
	
	function BaseSplitRequestHandler() {
		parent::RequestHandler();
	}

	function createSign() {
		parent::createSign();
		
		$this->setParameter("sign", strtoupper(
			$this->getParameter("sign")));
	}
	

}

?>