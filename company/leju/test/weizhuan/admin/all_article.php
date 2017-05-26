<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$page=$_GET['page'];

if(is_numeric($page)==false){
	$page=1;
}else{
	$page=$page+1;
}

$row=$mysql->query("select * from `article` order by `id` desc");

//批量操作
if($_POST){
	$chkArr=$_POST['checkbox'];	
	//批量删除
	if($_POST['do']=='del_art' && is_array($chkArr)){
		foreach($chkArr as $k => $v) {
			if(is_numeric($v)==false){continue;}
			$mysql->execute("delete from `article` where `id`='{$v}'");
		}
		echo "<script>alert('删除成功');location.href='all_article.php'</script>";
		exit;
	}
	//批量置顶
	if($_POST['do']=='set_top' && is_array($chkArr)){
		foreach($chkArr as $k => $v) {
			if(is_numeric($v)==false){continue;}
		$mysql->execute("update `article` set  `top` =  '1' where`id`='{$v}'");
		}
		echo "<script>alert('置顶成功');location.href='all_article.php'</script>";
		exit;
	}
	//取消置顶
	if($_POST['do']=='del_top' && is_array($chkArr)){
		foreach($chkArr as $k => $v) {
			if(is_numeric($v)==false){continue;}
		$mysql->execute("update `article` set  `top` =  '0' where`id`='{$v}'");
		}
		echo "<script>alert('置顶取消成功');location.href='all_article.php'</script>";
		exit;
	}	
}
//删除
$del_id=guolv($_GET['del_id']);
if(is_numeric($del_id)){
	$mysql->query("delete from `article` where `id`='{$del_id}'");
	echo "<script>alert('删除成功');location.href='all_article.php'</script>";
	exit;
}

//搜索
if($_GET['do']=='search'){
	$search_title=guolv($_GET['search_title']);
	$row=$mysql->query("select * from `article` where `title` LIKE  '%{$search_title}%' limit 1");
}
//排序
if($_GET['order']=='pv'){
	$row=$mysql->query("select * from `article` order by -`pv`");
}
//日期
$search_day=guolv($_GET['day']);
if(preg_match("/\d{4}-1[0-2]|0?[1-9]-0?[1-9]|[12][0-9]|3[01]/", $search_day)){
    $row=$mysql->query("select * from `article` where `day`='{$search_day}'");
}
?>

<?php include('head.php')?>    
<script type="text/javascript"> 
function check_all(obj,cName) 
{ 
    var checkboxs = document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
</script>
    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">文章管理</h1>
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
	<form class="navbar-search pull-left" action="all_article.php" method="get">
	  <input type="hidden" name="do" value="search">
	  <input type="text" class="search-query" placeholder="搜索文章标题+回车" name="search_title">
	</form>	
	<div class="btn-group pull-right">
	  <button class="btn"><a href="?day=<?php echo date("Y-m-d",time())?>"><?php echo date("Y-m-d",time())?></a></button>
	  <button class="btn dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
	  </button>
		<ul class="dropdown-menu">
		<?php
			for($i=1;$i<=30;$i++){
				$day=date('Y-m-d',strtotime('-'.$i.' day'));
				echo "<li><a href='?day={$day}'>{$day}</a></li>";
			}
		?>
        </ul>
		<button class="btn" style="margin-left:10px;"><a href="all_article.php">首页</a></button>
		 <button class="btn" style="margin-left:10px;"><a href="?page=<?php echo $page?>">下一页</a></button>
	</div>
    <table class="table">
	<form class="navbar-search pull-left" action="all_article.php" method="post">
      <thead>
        <tr>
		<th></th>
          <th>发布日期</th>
		  <th>分类</th>
          <th>标题(<a href="?order=pv">pv</a>)</th>
		  <th>操作</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_article=page_array(50,$page,$row,0);
	  foreach($row_article as $v_article){
	  $pic=str_replace('[weixin]','http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=500&h=400&url=',$v_article['pic']);
	  if($v_article['top']==1){
		  $top="<font color=red>[置顶]</font>";
	  }else{
		  $top='';
	  }
print <<<table
        <tr>
		  <td><input type="checkbox" name="checkbox[]" value="{$v_article['id']}"> </td>
          <td>{$v_article['day']}</td>
		  <td>{$v_article['type']}</td>
          <td>{$top} <a href="{$site}/detail.php?aid={$v_article['id']}" target="_blank">{$v_article['title']}</a>({$v_article['pv']})</td>
		  <td>
			<font color=grey>￥{$v_article['money']}</font> 
			<a class="btn btn btn-small" target="_blank" href="{$pic}" target="_blank" title="缩略图查看"><i class="icon-picture"></i></a>
			<a class="btn btn btn-small" target="_blank" href="edit_article.php?id={$v_article['id']}" title="修改"><i class="icon-edit"></i></a>
			<a class="btn btn btn-small" href="?del_id={$v_article['id']}" title="删除"><i class="icon-trash"></i></a>
		  </td>
        </tr>
table;

	  }
	  ?>  
      </tbody>
    </table>
	<div>
	<p>
	<span><input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" /> 全选 / 全不选</span>
	<span style="padding-left:20px;">
	批量操作：
	<select name="do" style="width:100px;">
		<option value="set_top">置顶</option>
		<option value="del_top">取消置顶</option>
		<option value="del_art">删除</option>
	</select>  	
	<input type="submit" class="btn btn btn-small" value="提交">
	</span>
	</p> 
	</div>
</div>
</form>
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


