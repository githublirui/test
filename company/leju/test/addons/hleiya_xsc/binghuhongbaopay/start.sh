#!/bin/sh
SERVICE='cron_binghuhongbao_pay.php'
 
if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is fine"
else
    echo "$SERVICE is not running"
sh /mnt/vdb1/virtualhost/male365/addons/hleiya_xsc/binghuhongbaopay/run.sh
fi
 
