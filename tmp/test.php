<?php

/**
 * 通过谷歌api生成二维码
 * $urlToEncode = "name:name;address:address";
  generateQRfromGoogle($urlToEncode);
 * @param type $chl
 * @param type $widhtHeight
 * @param type $EC_level
 * @param type $margin
 */
function generateQRfromGoogle($chl, $widhtHeight = '150', $EC_level = 'L', $margin = '0') {
    $chl = urlencode($chl);
    echo '<img src="http://chart.apis.google.com/chart?chs=' . $widhtHeight . 'x' . $widhtHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $chl . '" alt="QR code" widhtHeight="' . $widhtHeight . '" widhtHeight="' . $widhtHeight . '"/>';
}

