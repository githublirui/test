#!/bin/bash

PHP=php
BIN=/data/wwwroot/default/pintuhongbaopay/cron_pintuhongbao_pay.php
`nohup $PHP $BIN >>/dev/null 2>&1 &`
