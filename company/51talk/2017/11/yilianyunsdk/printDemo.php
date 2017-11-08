<?php
require_once (__DIR__.'/lib/YLYOpenApiClient.php');

$api = new YLYOpenApiClient();

$content = '';                          //打印内容
$content .= '<FS><center>8号桌</center></FS>';
$content .= str_repeat('-',32);
$content .= '<FS><table>';
$content .= '<tr><td>商品</td><td>数量</td><td>价格</td></tr>';
$content .= '<tr><td>土豆回锅肉</td><td>x1</td><td>￥20</td></tr>';
$content .= '<tr><td>干煸四季豆</td><td>x1</td><td>￥12</td></tr>';
$content .= '<tr><td>苦瓜炒蛋</td><td>x1</td><td>￥15</td></tr>';
$content .= '</table></FS>';
$content .= str_repeat('-',32)."\n";
$content .= '<FS>金额: 47元</FS>';

$machineCode = '';                      //授权的终端号
$accessToken = '';                      //api访问令牌
$originId = '';                         //商户自定义id
$timesTamp = time();                    //当前服务器时间戳(10位)
echo $api->printIndex($machineCode,$accessToken,$content,$originId,$timesTamp);