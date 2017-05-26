#!/bin/bash

PHP=/usr/local/php5.6/bin/php
BIN=/mnt/vdb1/virtualhost/male365/addons/hleiya_xsc/binghuhongbaopay/cron_binghuhongbao_pay.php
`nohup $PHP $BIN >>/dev/null 2>&1 &`
