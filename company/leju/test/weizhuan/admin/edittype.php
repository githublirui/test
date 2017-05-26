<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$id=guolv($_GET['id']);
if(is_numeric($id)){
	$row=$mysql->query("select * from `typedata` where `id` in({$id})");
}

//同步修改价格
//修改
if($_POST){
	$type_name=guolv($_POST['type_name']);
	$type_pp=guolv($_POST['type_pp']);
	$type_id=guolv($_POST['type_id']);
	$type_author=guolv($_POST['type_author']);
	if($type_name!==''){
		$mysql->query("update `typedata` set `name`='{$type_name}',`type_pp`='{$type_pp}',`type_author`='{$type_author}' where `id`='{$type_id}'");
		$mysql->query("update `article` set `money`='{$type_pp}' where `type`='{$type_id}'");
		echo "<script>alert('修改成功');location.href='edittype.php?id={$type_id}'</script>";
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
            <h1 class="page-title">分类修改</h1>
<div class="well">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
	  <div class="well">
<form id="tab" action="edittype.php" method="post">
        <label>分类名称</label>
        <input type="text" value="<?php echo $row[0]['name']?>" style="width:200px" name="type_name">
        <label>分类单价</label>
        <input type="text" value="<?php echo $row[0]['type_pp']?>" style="width:200px" name="type_pp">
        <label>公众号广告位（支持UBB）</label>
        <input type="text" value="<?php echo $row[0]['type_author']?>" style="width:500px" name="type_author" value=""> [author=http://www.baidu.com]公众号[/author]			
 <input type="hidden" value="<?php echo $id?>" name="type_id">			
		 <label></label>
		 <button class="btn btn-primary"><i class="icon-save"></i> 修改&同步分类文章价格</button>
    </form>
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


