<?php
$params = isset($_GET['params']) ? $_GET['params'] : false;

if($params == 'scriptsrc'){
    // script的src标签做javascript ajax的跨域处理
    $result = array('params' => $params, 'description' => 'script的src标签做javascript ajax的跨域处理');
    $returns = "var result = " . json_encode($result);
    echo $returns;die;
}elseif($params == 'xmlhttprequest'){
    // xmlhttprequest做javascript ajax的跨域处理
    // 请求过来时, 响应头要加上此句, 允许xmlhttprequest能访问的所有域名
    header("Access-Control-Allow-Origin: *");
    $returns = array('params' => $params, 'description' => 'xmlhttprequest做javascript ajax的跨域处理');
    echo json_encode($returns);die;
}
?>