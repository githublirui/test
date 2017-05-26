<?php

$postname = true;
$filename = 456;
$b = $postname ? : $filename;
var_dump($b);
die;

curl_init();
$url = 'http://test.local/index.php/company/ganji/2014/12/tmp.php';
$file = "C:/Users/lirui1/Pictures/test/1.jpg";
$filec = curl_file_create($file);
$post_fields = array(
    'img_width' => '500',
    'img_height' => '500',
    'category_id' => '2',
    'nowatermark' => '2',
    'file[0]' => $filec,
//    'file[1]' => '@C:/Users/lirui1/Pictures/test/1.jpg',
);
$header = array(
//            'Content-Type:application/json',
    'X-Ganji-CustomerId:705',
    'X-Ganji-VersionId:6.0.0',
    'X-Ganji-InstallId:C6FE4A2140D0632A4C41B8B8BA138582',
    'X-Ganji-ClientAgent:iphone#640*1136',
    'X-Ganji-Agency:appstore',
    'X-Ganji-Token:534352745756576b7473754a31777344534333304270676a',
        //'X-Ganji-Agent:H5',
);
$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_VERBOSE, 0);
// curl_setopt($ch, CURLOPT_NOBODY, true);
$result = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
var_dump($result);
die;
?>
