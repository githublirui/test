#!/bin/bash

#赶集生活天气数据更新
PHP=/usr/local/webserver/php/bin/php
SERVICE_BIN=/home/lirui1/www/ganji/ganji_online/mobile_client/app/datashare/daemon/crontabtest.php
args=($@);
job=${args[0]};
#echo "$PHP $SERVICE_BIN $job"
$PHP $SERVICE_BIN $job >>/dev/null 2>&1 &