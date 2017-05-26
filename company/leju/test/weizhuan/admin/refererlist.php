<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$page=$_GET['page'];
if(is_numeric($page)==false){
	$page=1;
	$page_1=1;
	$min=0;
	$page_2=$page+1;
}else{
	if($page>1){
		$page_2=$page+1;
		$min=($page-1)*35;
		$page_1=$page-1;	
	}else{
		$page_1=1;
		$min=0;
		$page_2=2;
	}
	
}
$max=$page*35;
$row=$mysql->query("select * from `refererdata` order by `id` desc limit {$min},{$max}");

//查看分享记录
$uid=guolv($_GET['uid']);
if(is_numeric($uid)){
	$row=$mysql->query("select * from `refererdata` where `uid`='{$uid}' order by `id` desc limit {$min},{$max}");
}

//查看ip
$kip=guolv($_GET['ip']);
if(is_ip($kip)){
	$row=$mysql->query("select * from `refererdata` where `ip`='{$kip}' order by `id` desc limit {$min},{$max}");
}

?>
<script>
	function parseip(remote_ip_info,ip){
			if(remote_ip_info.ret=='-1')
	{
		$("span[ip='"+ip+"']").html('局域网地址');
	}
	else
	{
		var data = '';
		//if(remote_ip_info.country !='') data = remote_ip_info.country + ',';
		if(remote_ip_info.province !='') data = data + remote_ip_info.province + ',';
		if(remote_ip_info.city !='') data = data + remote_ip_info.city + ',';
		if(remote_ip_info.district !='') data = data + remote_ip_info.district + ',';
		if(remote_ip_info.isp !='') data = data + remote_ip_info.isp + ',';
		$("span[ip='"+ip+"']").html(data);
	}
	
	}
</script>
<?php include('head.php')?>    

    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">IP统计</h1>
<!--			
<div class="btn-toolbar">
    <a href="#myModal" data-toggle="modal" class="btn">Delete</a>
  <div class="btn-group">
  </div>
</div>
-->
<div class="well">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
		<div class="well">
<div class="pull-right">
<a href="refererlist.php" class="btn">首页</a>
<a href="refererlist.php?page=<?php echo $page_1?>" class="btn" style="margin-left:5px;">上一页</a>
<a href="refererlist.php?page=<?php echo $page_2?>" class="btn" style="margin-left:5px;">下一页</a>
</div>		
    <table class="table">
      <thead>
        <tr>
          <th>用户ID</th>
		  <th>访问页面</th>
		  <th>IP</th>
		  <th>归属地</th>
		  <th>时间</th>
        </tr>
      </thead>
      <tbody>
<?php
//$row_referer=page_array(50,$page,$row,0);
	  foreach($row as $v_referer){
	  $time=date("Y-m-d H:i",$v_referer['time']);
print <<<table
        <tr>
          <td><a href="?uid={$v_referer['uid']}">{$v_referer['uid']}</a></td>
		  <td><a href="{$site}/detail.php?aid={$v_referer['aid']}" target="_blank">{$v_referer['title']}</a></td>
		  <td><a href="?ip={$v_referer['ip']}">{$v_referer['ip']}</a></td>
		  <td><span ip="{$v_referer['ip']}">归属地</span></td>
		  <td>{$time}</td>
        </tr>	
<script src="http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip={$v_referer['ip']}"></script>		
<script>
parseip(remote_ip_info,'{$v_referer['ip']}');
</script>
table;
	  }
	  ?>  
      </tbody>
    </table>
	<div class="pagination">
  <ul>
    <!--<li class="disabled"><a href="#">&laquo;</a></li>-->
  </ul>
</div>
</div>
      </div>
      <div class="tab-pane fade" id="profile">
      </div>
  </div>

</div>

        </div>
    </div>
    

    
    <footer>
        <hr>
        
        <p class="pull-right"><!--power by right--></p>
        
        
        <p><!--power by --></p>
    </footer>
    

    

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="lib/bootstrap/js/bootstrap.js"></script>
	


  </body>
</html>


