<?php

$fbztcontent = '<img src=\"/../../../../../upload/20130806/13757938687546.jpg\" title=\"1.jpg\"/>';
	preg_match('/<img\s+src=\\\?".+?(upload.+?)\\\?"\s+[^>]+\/>/is', $fbztcontent, $pic);
//	preg_match('/<img\s+src=\\\?/is', $fbztcontent, $pic);
//	$pic = $pic[1];
// var_dump($pic);die;

var_dump(realpath('../'));die;