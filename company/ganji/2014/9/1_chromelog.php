<?php

/**
 * 
 * 谷歌浏览器log
 */
include dirname(__FILE__) . '/../lib/chromephp-master/ChromePhp.php';
ChromePhp::log('Hello console!');
ChromePhp::log($_SERVER);
ChromePhp::warn('something went wrong!');
