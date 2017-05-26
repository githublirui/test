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
$row=$mysql->query("select * from `userdata` order by `id` desc");

//删除会员
$del_id=guolv($_GET['del_id']);
if(is_numeric($del_id)){
	$mysql->query("delete from `userdata` where `id`='{$del_id}'");
	echo "<script>alert('删除成功');location.href='user.php'</script>";
	exit;
}
//搜索
if($_GET['do']=='search'){
	$phone=guolv($_GET['phone']);
	$row=$mysql->query("select * from `userdata` where `phone`='{$phone}' limit 1");
}
//用户收益排行
if($_GET['do']=='money'){
	$phone=guolv($_GET['phone']);
	$row=$mysql->query("select * from `userdata` order by -`money`");
}
?>

<?php include('head.php')?>    

    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">用户管理</h1>

<div class="well">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
		<div class="well">
<form class="navbar-search pull-left" action="user.php" method="get">
  <input type="hidden" name="do" value="search">
  <input type="text" class="search-query" placeholder="搜索会员+回车" name="phone">
</form>
<div class="pull-right">
<a href="user.php" class="btn">首页</a></button>
<a href="?page=<?php echo $page?>" class="btn">下一页</a></button>
</div>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>手机号</th>
		  <th><a href="?do=money">总收益</a></th>
		  <th>注册时间</th>
		  <th>操作</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_user=page_array(30,$page,$row,0);
	  foreach($row_user as $v_user){
	  $time=date("Y-m-d H:i:s",$v_user['time']);
	  if($v_user['phone']==''){
			$_phone='<font color=grey>微信用户</font>';
	  }else{
			$_phone=$v_user['phone'];
	  }
print <<<table
        <tr>
          <td><a href="refererlist.php?uid={$v_user['id']}" title="查看分享记录">{$v_user['id']}</a></td>
          <td>{$_phone}</td>
		  <td>{$v_user['money']}</td>
		  <td>{$time}</td>
		  <td>
			<a class="btn btn-primary" href="edit_user.php?uid={$v_user['id']}" title="编辑"><i class="icon-edit"></i></a>
			<a class="btn" href="?del_id={$v_user['id']}" title="删除用户"><i class="icon-trash"></i></a>
		  </td>
        </tr>
table;

	  }
	  ?>  
      </tbody>
    </table>
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


