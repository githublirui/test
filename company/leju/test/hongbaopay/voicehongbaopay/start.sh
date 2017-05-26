#!/bin/sh
SERVICE='cron_voicehongbao_pay.php'
 
if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is fine"
else
    echo "$SERVICE is not running"
sh /var/www/html/voicehongbaopay/run.sh
fi
 
