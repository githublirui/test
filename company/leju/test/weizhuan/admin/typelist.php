<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');

//删除分类
$del_id=guolv($_GET['del_id']);
if(is_numeric($del_id)){
	$mysql->execute("delete from `typedata` where `id`='{$del_id}'");
	echo "<script>alert('删除成功');location.href='typelist.php'</script>";
	exit;	
}

//增加
if($_POST){
	$type_name=guolv($_POST['type_name']);
	$type_pp=guolv($_POST['type_pp']);
	$type_author=guolv($_POST['type_author']);
	if($type_name!==''){
		$mysql->query("insert into `typedata` values(null,'{$type_name}','{$type_pp}','{$type_author}')");
		$id=mysql_insert_id();
		if($id!==0){
			echo "<script>alert('增加成功');location.href='typelist.php'</script>";
			exit;
		}
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
            <h1 class="page-title">分类管理</h1>
<div class="well">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
	  <div class="well">
<form id="tab" action="typelist.php" method="post">
        <label>增加分类</label>
        <input type="text" value="" style="width:200px" name="type_name">
        <label>分类单价</label>
        <input type="text" style="width:200px" name="type_pp" value="0.05">		
        <label>公众号广告位（支持UBB）</label>
        <input type="text" style="width:500px" name="type_author" value=""> [author=http://www.baidu.com]公众号[/author]	
		 <label></label>
		 <button class="btn btn-primary"><i class="icon-save"></i> 提交</button>
    </form>
	  </div>
		<div class="well">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>分类名称</th>
		  <th>分类单价</th>
		  <th>操作</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_type=$mysql->query("select * from `typedata` order by `id` desc");
	  foreach($row_type as $v_type){
print <<<table
        <tr>
          <td>{$v_type['id']}</td>
          <td>{$v_type['name']}</td>
		  <td>{$v_type['type_pp']}</td>
		  <td>
			<a class="btn btn-primary btn-small" taget="_blank" href="edittype.php?id={$v_type['id']}"><i class="icon-edit"></i></a>
			<a class="btn btn btn-small" href="?del_id={$v_type['id']}"><i class="icon-calendar"></i></a>
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


