#!/bin/bash

PHP=/usr/local/webserver/php/bin/php
BIN=/home/lirui1/www/ganji/ganji_online/mobile_client/app/push/daemon/UserPush.class.php

cd /home/lirui1/www/ganji/ganji_online/mobile_client/app/push/daemon

for((num=0;num<=2;num++))
do
  for((i=0;i<=9;i++))
  do
	`nohup $PHP $BIN $num $i >>/dev/null 2>&1 &`
#	$PHP $BIN $num $i >>/data/home/chenwei5/www/ganji/ganji_online/mobile_client/app/dev/yunying/MSC_7819_$num.log 2>&1 &
  done
done
