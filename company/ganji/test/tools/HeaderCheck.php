<?php


class HeaderCheck {

	static public $param;

	static public function checkHeader()
	{
		if(!empty(HeaderCheck::$param))
		{
			return;
		}

		HeaderCheck::$param['customerId'] = $_SERVER['HTTP_CUSTOMERID'];
		HeaderCheck::$param['clientAgent'] = $_SERVER['HTTP_CLIENTAGENT'];
		HeaderCheck::$param['versionId'] = $_SERVER['HTTP_VERSIONID'];
		HeaderCheck::$param['model'] = $_SERVER['HTTP_MODEL'];
		HeaderCheck::$param['agency'] = $_SERVER['HTTP_AGENCY'];
		HeaderCheck::$param['contentformat'] = $_SERVER['HTTP_CONTENTFORMAT'];
		HeaderCheck::$param['GjDataVersion'] = $_SERVER['HTTP_GJDATA_VERSION'];
		HeaderCheck::$param['interface'] = $_SERVER['HTTP_INTERFACE'];
        HeaderCheck::$param['token'] = $_SERVER['HTTP_TOKEN'];
        HeaderCheck::$param['schema'] = $_SERVER['HTTP_SCHEMA'];
        HeaderCheck::$param['subscribeTime'] = $_SERVER['HTTP_SUBSCRIBETIME'];
        HeaderCheck::$param['SeqID'] = $_SERVER['HTTP_SEQID'];
		if ($_SERVER['HTTP_USERID'])
		{
			HeaderCheck::$param['userId']= UUIDprocess::decryptUUId($_SERVER['HTTP_USERID']);
			HeaderCheck::$param['rawUserId'] = $_SERVER['HTTP_USERID'];
		}


		//检测数据用户标识customerId
		if (HeaderCheck::$param['customerId'] <= 0 || 
            !(HeaderCheck::$param['plat'] = ClientCustomerID::getPlatForm(HeaderCheck::$param['customerId']))) {
			echo self::getErrMsg(errDef::ERROR_MISS_CUSTOMERID);
			return false;
		}
		//检测机型屏幕clientAgent
		if (! HeaderCheck::$param['clientAgent']) {
			echo self::getErrMsg(errDef::ERROR_MISS_CLIENTAGENT);
			return false;
		}
		//检测客户端软件版本versionId
		if (!HeaderCheck::$param['versionId']) {
			echo self::getErrMsg(errDef::ERROR_MISS_VERSIONID);
			return false;
		}
		//检测平台/机型系列model
		if (!HeaderCheck::$param['model']) {
			echo self::getErrMsg(errDef::ERROR_MISS_MODEL);
			return false;
		}
		//检测用户标识userId
		if(HeaderCheck::$param['interface'] !='userRegister' && HeaderCheck::$param['interface'] !='UserRegister'){
			if (!isset(HeaderCheck::$param['userId'])||HeaderCheck::$param['userId'] < 0) {
				echo self::getErrMsg(errDef::ERROR_MISS_USERID);
				return false;
			}
		}
/*		if (!HeaderCheck::$param['GjDataVersion']) {
			echo self::getErrMsg(errDef::ERROR_MISS_GJDATAVERSION);
			return false;
		}*/
		return true;
	}
	public function getErrMsg($code)
	{   
		$args=array(
				'Code' => $code,
			   );  

		if(isset(errDef::$error_msg[$code])){

			$args['Message']=errDef::$error_msg[$code];
		}   

		if(isset(errDef::$error_detail[$code])){

			$args['Detail']=errDef::$error_detail[$code];
		}   
		$args['StatusCode'] = $code;
		
		if(isset(errDef::$error_state[$code])){
			header('HTTP/1.1 '.errDef::$error_state[$code]);
		}
		return json_encode(array($args));

	}   

}	
