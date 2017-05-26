<?php

//---------------------------------------------------------
//�Ƹ�ͨ��ʱ����֧����̨�ص�ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/ResponseHandler.class.php");
require ("classes/RequestHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");

/* �̻��� */
$partner = "1900000109";

/* ��Կ */
$key = "8934e7d15453e97507ef794cf7b0519d";


/* ����֧��Ӧ����� */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//�ж�ǩ��
if($resHandler->isTenpaySign()) {
	
	//֪ͨid
	$notify_id = $resHandler->getParameter("notify_id");
	
	//ͨ��֪ͨID��ѯ��ȷ��֪ͨ�����Ƹ�ͨ
	//������ѯ����
	$queryReq = new RequestHandler();
	$queryReq->init();
	$queryReq->setKey($key);
	$queryReq->setGateUrl("https://gw.tenpay.com/gateway/verifynotifyid.xml");
	$queryReq->setParameter("partner", $partner);
	$queryReq->setParameter("notify_id", $notify_id);
	
	//ͨ�Ŷ���
	$httpClient = new TenpayHttpClient();
	$httpClient->setTimeOut(5);
	//������������
	$httpClient->setReqContent($queryReq->getRequestURL());
	
	//��̨����
	if($httpClient->call()) {
		//���ý������
		$queryRes = new ClientResponseHandler();
		$queryRes->setContent($httpClient->getResContent());
		$queryRes->setKey($key);
		
		//�ж�ǩ�������
		//ֻ��ǩ����ȷ,retcodeΪ0��trade_stateΪ0����֧���ɹ�
		if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $queryRes->getParameter("trade_state") == "0" && $queryRes->getParameter("trade_mode") == "1" ) {
			//ȡ���������ҵ����
			$out_trade_no = $queryRes->getParameter("out_trade_no");
			//�Ƹ�ͨ������
			$transaction_id = $queryRes->getParameter("transaction_id");
			//���,�Է�Ϊ��λ
			$total_fee = $queryRes->getParameter("total_fee");
			//�����ʹ���ۿ�ȯ��discount��ֵ��total_fee+discount=ԭ�����total_fee
			$discount = $queryRes->getParameter("discount");
			
			//------------------------------
			//����ҵ��ʼ
			//------------------------------
			
			//�������ݿ��߼�
			//ע�⽻�׵���Ҫ�ظ�����
			//ע���жϷ��ؽ��
			
			//------------------------------
			//����ҵ�����
			//------------------------------
			echo "success";
			
		} else {
			//����ʱ�����ؽ������û��ǩ����д��־trade_state��retcode��retmsg��ʧ�����顣
			//echo "��֤ǩ��ʧ�� �� ҵ�������Ϣ:trade_state=" . $queryRes->getParameter("trade_state") . ",retcode=" . $queryRes->getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
			echo "fail";
		}
		
		//��ȡ��ѯ��debug��Ϣ,���������Ӧ�����ݡ�debug��Ϣ��ͨ�ŷ�����д����־�����㶨λ����
		/*
		echo "<br>------------------------------------------------------<br>";
		echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
		echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
		echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
		echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
		echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
		*/
	}else {
		//ͨ��ʧ��
		echo "fail";
		//��̨����ͨ��ʧ��,д��־�����㶨λ����
		//echo "<br>call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
	} 
	
	
} else {
	//�ص�ǩ������
	echo "fail";
	//echo "<br>ǩ��ʧ��<br>";
}

//��ȡdebug��Ϣ,�����debug��Ϣд����־�����㶨λ����
//echo $resHandler->getDebugInfo() . "<br>";

?>