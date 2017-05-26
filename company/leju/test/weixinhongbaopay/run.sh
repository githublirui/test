#!/bin/bash

PHP=php
BIN=/var/www/html/weixinhongbaopay/cron_weixin_pay.php
`nohup $PHP $BIN >>/dev/null 2>&1 &`
