<?php

require_once (__DIR__.'/lib/YLYTokenClient.php');
$token = new YLYTokenClient();

//获取token;
$grantType = 'client_credentials';  //自有模式(client_credentials) || 开放模式(authorization_code)
$scope = 'all';                     //权限
$timesTamp = time();                //当前服务器时间戳(10位)
//$code = '';                       //开放模式(商户code)
echo $token->GetToken($grantType,$scope,$timesTamp);

//刷新token;
$grantType = 'refresh_token';       //自有模式或开放模式一致
$scope = 'all';                     //权限
$timesTamp = time();                //当前服务器时间戳(10位)
$RefreshToken = '';                 //刷新token的密钥
echo $token->RefreshToken($grantType,$scope,$timesTamp,$RefreshToken);




