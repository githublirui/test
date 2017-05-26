<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
//print_r($config);
//设置
//管理员设置
if($_POST['do']=='admin'){
	$admin_username=guolv($_POST['admin_username']);
	$admin_password=guolv($_POST['admin_password']);
	$admin_q=guolv($_POST['admin_q']);
	$row=$mysql->query("select * from `admindata` where `username`='{$admin_username}' limit 1");
	if(!$row){
		$mysql->query("insert into `admindata` values(null,'{$admin_username}','{$admin_password}','{$admin_q}')");
		$id=mysql_insert_id();
		if($id!==0){
			echo "<script>alert('增加成功！');location.href='set_admin.php'</script>";
			exit;		
		}		
	}
}

//管理员删除
if(is_numeric($_GET['del_admin'])){
	$del_admin=guolv($_GET['del_admin']);
	$mysql->query("delete from `admindata` where `id`='{$del_admin}'");
	echo "<script>alert('删除成功！');location.href='set_admin.php'</script>";
	exit;		
}
?>

<?php include('head.php')?>    
    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">系统设置</h1>
			
<div class="well">
    <ul class="nav nav-tabs">
	  <li class="active"><a href="#admin" data-toggle="tab">管理员设置</a></li>
	  
    </ul>
    <div id="myTabContent" class="tab-content">  
	  <div class="tab-pane active in" id="admin">
		<form action="set_admin.php" method="post">
		<input type="hidden" name="do" value="admin">
	    <label>管理员：</label>
        <input type="text" value="" style="width:210px" name="admin_username">	
	    <label>密码：</label>
        <input type="text" value="" style="width:210px" name="admin_password">	
		 <label>权限：</label>
		 <select name="admin_q">
		<option value="最高权限">最高权限</option>
		<option value="财务管理">财务管理</option>
		<option value="分享文章">分享文章</option>
		<option value="CPS管理">CPS管理</option>
		<option value="网站设置">网站设置</option>	
		</select>
 <div>
            <button class="btn btn-primary">提交</button>
        </div>
		</form>
		<div class="well">
    <table class="table">
      <thead>
        <tr>
          <th>管理员</th>
		  <th>角色</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_admin=$mysql->query("select * from `admindata` order by `id` desc");
	  foreach($row_admin as $v_admin){
print <<<table
        <tr>
          <td>{$v_admin['username']}</td>
		   <td>{$v_admin['q']}</td>
		  <td>
			<a class="btn btn" href="?del_admin={$v_admin['id']}">删除</a>
		  </td>
        </tr>
table;

}
	  ?>  
      </tbody>
    </table>
</div>		
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


