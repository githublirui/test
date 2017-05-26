<?php

echo urlencode('北京');die;
$cat = 'cat';
for($i=0;$i<=24;$i++) {
  $cat .= ' pushuser_'.sprintf("%02d",$i);
}
$cat .=' >> total_push';
echo $cat;