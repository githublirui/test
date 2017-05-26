<?php

require dirname(__FILE__) . '/lib/PHPQRCode.php';

\PHPQRCode\QRcode::png("http://www.baidu.com", "./qrcode.png", 'L', 4, 2);
