<?php
#��ΪCURLģ���¼DZ
$discuz_url = 'https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN'; //��̳��ַ
//$login_url = $discuz_url . 'logging.php?action=login'; //��¼ҳ��ַ

$post_fields = array();
//���������Ҫ�޸�
$post_fields['f'] = 'json';
$post_fields['imgcode'] = '';
//�û��������룬������д
$post_fields['username'] = 'zimumdm@163.com';
$post_fields['password'] = md5('mdm516516');


//POST���ݣ���ȡCOOKIE,cookie�ļ�������վ��tempĿ¼��
$cookie_file = tempnam('./temp', 'cookie');

$ch = curl_init($discuz_url);


curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).'/weixin.qq.pem');

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_exec($ch);

var_dump(curl_error($ch));

curl_close($ch);