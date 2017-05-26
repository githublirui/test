#!/bin/sh
SERVICE='cron_weixin_pay.php'
 
if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is fine"
else
    echo "$SERVICE is not running"
sh /var/www/html/weixinpay/run.sh
fi
 
