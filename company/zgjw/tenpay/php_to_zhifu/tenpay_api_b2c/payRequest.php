<?php
//---------------------------------------------------------
//�Ƹ�ͨ��ʱ����֧������ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require_once ("classes/RequestHandler.class.php");

/* �̻��ţ�����ʱ��ؽ������̻����滻Ϊ��ʽ�̻��� */
$partner = "1217074301";

/* ��Կ */
$key = "187f9720ccaf0a0222d510f4aae2866a";




//4λ�����
$randNum = rand(1000, 9999);

//�����ţ��˴���ʱ�����������ɣ��̻������Լ����������ֻҪ����ȫ��Ψһ����
$out_trade_no = date("YmdHis") . $randNum;



/* ����֧��������� */
$reqHandler = new RequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

//----------------------------------------
//����֧������ 
//----------------------------------------
$reqHandler->setParameter("total_fee", "1");  //�ܽ��
//�û�ip
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//�ͻ���IP
$reqHandler->setParameter("return_url", "http://localhost:8080/payReturnUrl.php");//֧���ɹ��󷵻�
$reqHandler->setParameter("partner", $partner);
$reqHandler->setParameter("out_trade_no", $out_trade_no);
$reqHandler->setParameter("notify_url", "http://localhost:8080/payNotifyUrl.php");
$reqHandler->setParameter("body", "����");
$reqHandler->setParameter("bank_type", "DEFAULT");  	  //�������ͣ�Ĭ��Ϊ�Ƹ�ͨ
$reqHandler->setParameter("fee_type", "1");               //����
//ϵͳ��ѡ����
$reqHandler->setParameter("sign_type", "MD5");  	 	  //ǩ����ʽ��Ĭ��ΪMD5����ѡRSA
$reqHandler->setParameter("service_version", "1.0"); 	  //�ӿڰ汾��
$reqHandler->setParameter("input_charset", "GBK");   	  //�ַ���
$reqHandler->setParameter("sign_key_index", "1");    	  //��Կ���

//ҵ���ѡ����
$reqHandler->setParameter("attach", "");             	  //�������ݣ�ԭ�����ؾͿ�����
$reqHandler->setParameter("product_fee", "");        	  //��Ʒ����
$reqHandler->setParameter("transport_fee", "");      	  //��������
$reqHandler->setParameter("time_start", date("YmdHis"));  //��������ʱ��
$reqHandler->setParameter("time_expire", "");             //����ʧЧʱ��

$reqHandler->setParameter("buyer_id", "");                //�򷽲Ƹ�ͨ�ʺ�
$reqHandler->setParameter("goods_tag", "");               //��Ʒ���




//�����URL
$reqUrl = $reqHandler->getRequestURL();

//��ȡdebug��Ϣ,����������debug��Ϣд����־�����㶨λ����
/*
$debugInfo = $reqHandler->getDebugInfo();
echo "<br/>" . $reqUrl . "<br/>";
echo "<br/>" . $debugInfo . "<br/>";
*/

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�Ƹ�ͨ��ʱ���ʳ�����ʾ</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">�Ƹ�֧ͨ��</a>
<form action="<?php echo $reqHandler->getGateUrl() ?>" method="post" target="_blank">
<?php
$params = $reqHandler->getAllParameters();
foreach($params as $k => $v) {
	echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
}
?>
<input type="submit" value="�Ƹ�֧ͨ��">
</form>
</body>
</html>
