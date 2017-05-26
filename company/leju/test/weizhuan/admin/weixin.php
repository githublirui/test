<?php
session_start();
require('../conn.php');
require('../functions.php');
require('../QueryList.class.php');
require('admin.php');
if($_POST){
	$long=guolv(trim($_POST['long']));
	$type_arr=explode('#',guolv($_POST['type']));
	$type=$type_arr[0];
	$money=$type_arr[1];	
	$html=get_contents($long);
	$html=str_replace('data-src','src',$html);
	$vid=cut($html,'vid=','&');//获取视频ID
	$caiji = array(
		"title"=>array(".rich_media_title:first","text"),
		"content"=>array("#js_content","html"),
		
		);
	$quyu='';
	$hj = QueryList::Query($html,$caiji,$quyu);
	$arr = $hj->jsonArr;
	$title=$arr[0]['title'];
	$content=preg_replace("/<(\/?i?frame.*?)>/si","",$arr[0]['content']); //过滤frame标签
	if($vid!==''){
		$content="<p><iframe height=300 width=100% src=\"http://v.qq.com/iframe/player.html?vid={$vid}\" frameborder=0 allowfullscreen></iframe></p>".$content;
	}
	$pic=cut($html,'var msg_cdn_url = "','"');
	if(url_exists($long)==1){
		echo "<script>alert('网址不存在');location.href='weixin.php'</script>";
		exit;		
	}
	if(is_numeric($type)==false){
		echo "<script>alert('分类不存在');location.href='weixin.php'</script>";
		exit;		
	}	
	$row=$mysql->query("select * from `article` where `title`='{$title}' limit 1");
	if(!$row){
		$arr=array(
			//'id'=>null,
			'top'=>0,
			'title'=>$title,
			'content'=>$content,
			'pic'=>'[weixin]'.$pic,
			'type'=>$type,
			'pv'=>0,
			'pv_max'=>'-1',
			'money'=>$money,
			'day'=>date("Y-m-d",time()),
		);
		$value=arr2s($arr);
		$mysql->query("insert into `article` {$value}");
		$id=mysql_insert_id();
		if($id!==0){
			echo "<script>location.href='edit_article.php?id={$id}'</script>";
			exit;
		}else{
			echo "<script>alert('发布失败');location.href='weixin.php'</script>";
			exit;
		}
	}else{
		echo "<script>location.href='edit_article.php?id={$row[0]['id']}'</script>";
		exit;		
	}
}
?>

<?php include('head.php')?>    
    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">微信文章导入</h1>
<!--			
<div class="btn-toolbar">
    <a href="#myModal" data-toggle="modal" class="btn">Delete</a>
  <div class="btn-group">
  </div>
</div>
-->
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">发布</a></li>
      <li><a href="http://weixin.sogou.com" target="_blank">更多微信文章</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">	  
    <form id="tab" action="weixin.php" method="post">
	<select name="type" class="txt-input txtpd">
<?php
$row_type=$mysql->query("select * from `typedata` order by `id` desc");
foreach($row_type as $v_type){
	echo "<option value=\"{$v_type['id']}#{$v_type['type_pp']}\">{$v_type['name']} (单价：{$v_type['type_pp']})</option>";
}
?>
                      </select>				
        <label>微信公众号网址：</label>
        <input type="text" value="" style="width:500px" name="long"><br />
		<p>例如：<code>http://mp.weixin.qq.com/s?__biz=MjM5OTA2MzE3Mg==&mid=234288765&idx=2&sn=ed9e013786f33d51326125a61b0af8b8&3rd=MzA3MDU4NTYzMw==&scene=6#rd</code></p>	
		<label></label>
		 <button class="btn btn-primary"><i class="icon-save"></i> 提交</button>
    </form>
      </div>
      <div class="tab-pane fade" id="profile">
    <form id="tab2">
        <label>New Password</label>
        <input type="password" class="input-xlarge">
        <div>
            <button class="btn btn-primary">Update</button>
        </div>
    </form>
      </div>
  </div>

</div>

<div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Delete Confirmation</h3>
  </div>
  <div class="modal-body">
    
    <p class="error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete the user?</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button class="btn btn-danger" data-dismiss="modal">Delete</button>
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


