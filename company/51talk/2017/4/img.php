<?php

include_once LIB_PATH . '/utils/UtilsImage.class.php';
UtilsImage::square_crop($GLOBALS['now_path'] . '/pano_back.jpg', $GLOBALS['now_path'] . '/pano_back1.jpg', '1500');
UtilsImage::square_crop($GLOBALS['now_path'] . '/pano_back.jpg', $GLOBALS['now_path'] . '/pano_back2.jpg', '400');
//$image->scale(1.22);
//$image->save($GLOBALS['now_path'] . '/pano_back2.jpg');
?>
