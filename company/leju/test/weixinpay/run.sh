#!/bin/bash

PHP=php
BIN=/var/www/html/weixinpay/cron_weixin_pay.php
`nohup $PHP $BIN >>/dev/null 2>&1 &`
