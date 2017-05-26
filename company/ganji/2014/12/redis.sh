# !/bin/bash 

for  ((i = 0 ;i < 100 ;i ++ )); do 
{
    cd /home/chenwei5/www/ganji/ganji_online/mobile_client/app/dev/cron/
    php redisPushTest.php 
}& 
done

