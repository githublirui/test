#!/bin/sh
SERVICE='cron_pintuhongbao_pay.php'
 
if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is fine"
else
    echo "$SERVICE is not running"
sh /data/wwwroot/default/pintuhongbaopay/run.sh
fi
 
