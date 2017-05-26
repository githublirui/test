#!/bin/bash

PHP=php
BIN=/var/www/html/voicehongbaopay/cron_voicehongbao_pay.php
`nohup $PHP $BIN >>/dev/null 2>&1 &`
