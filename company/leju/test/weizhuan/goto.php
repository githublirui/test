<?php
require('conn.php');
$url=$config['site'].'/reg_wx.php';
?>
<script>location.href='http://www.weixingate.com/gate.php?back=<?php echo urlencode($url)?>&force=1'</script>