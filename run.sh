#!/bin/bash

PHP=/usr/local/webserver/php/bin/php
BIN=/home/lirui1/www/ganji/ganji_online/mobile_client/tmp/run/stat.php

cd /home/lirui1/www/ganji/ganji_online/mobile_client/tmp/run

for((num=0;num<=49;num++))
do
    #`nohup $PHP $BIN $num >>/dev/null 2>&1 &`
     $PHP $BIN $num $i >>/home/lirui1/log/user_stat_$num.log 2>&1 &
done
