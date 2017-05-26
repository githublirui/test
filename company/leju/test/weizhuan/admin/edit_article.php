<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$id=guolv($_GET['id']);
$row=$mysql->query("select * from `article` where `id`='{$id}'");

if($_POST){
	//文章编辑
		$title=guolv($_POST['title']);
		$content=guolv($_POST['content']);
		$pv_max=guolv($_POST['pv_max']);//浏览次数
		$money=guolv($_POST['money']);//浏览次数
		$aid=guolv($_POST['id']);
		$mysql->execute("update `article` set `title`='{$title}',`content`='{$content}',`pv_max`='{$pv_max}',`money`='{$money}' where `id`='{$aid}'");
		echo "<script>alert('修改成功');location.href='all_article.php'</script>";
		exit;
}
?>

<?php include('head.php')?>    
	<link rel="stylesheet" href="../editor/themes/default/default.css" />
	<link rel="stylesheet" href="../editor/plugins/code/prettify.css" />
	<script src="static/jquery.js" type="text/javascript"></script> 
	<script charset="utf-8" src="../editor/kindeditor.js"></script>
	<script charset="utf-8" src="../editor/lang/zh_CN.js"></script>
	<script charset="utf-8" src="../editor/plugins/code/prettify.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content"]', {
				cssPath : '../editor/plugins/code/prettify.css',
				uploadJson : '../editor/php/upload_json.php',
				fileManagerJson : '../editor/php/file_manager_json.php',
				allowFileManager : true,
			});
			prettyPrint();
		});
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
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">发布</a></li>
      <!--<li><a href="#profile" data-toggle="tab">图片上传</a></li>-->
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">	  
    <form id="tab" action="edit_article.php" method="post">
		<input type="hidden" name="id" value="<?php echo $id?>">
        <label>标题</label>
        <input type="text" value="<?php echo $row[0]['title']?>" style="width:500px" name="title">
		<label></label>
        <!--<input type="text" value="" style="width:500px" name="pic">
		<input name="filename" type="file">-->
         <label>浏览次数(-1，表示无限)</label>
		 <input style="width: 250px;" type="text" id="img" value="<?php echo $row[0]['pic']?>" name="pic"/>	
        <input type="text" value="<?php echo $row[0]['pv_max']?>" style="width:200px" name="pv_max"> 累计收益：<?php echo $row[0]['pv']*$row[0]['money']?>元
		<label>浏览单价：</label>
        <input type="text" value="<?php echo $row[0]['money']?>" style="width:200px" name="money">		
		<textarea value="Smith" rows="10" name="content" style="width:800px;height:400px;visibility:hidden;"><?php echo $row[0]['content']?></textarea>
		 <label></label>
		 <button class="btn btn-primary"><i class="icon-save"></i> 保存</button>
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


