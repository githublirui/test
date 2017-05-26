<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$id=guolv($_GET['id']);
if(is_numeric($id)){
	$row=$mysql->query("select * from `newsdata` where `id` in({$id})");
}
if($_POST){
	//新闻修改
		$news_title=guolv($_POST['news_title']);
		$news_content=guolv($_POST['news_content']);
		$news_id=guolv($_POST['news_id']);
		$time=time();
		if($news_title!=='' && $news_content!==''){
			$mysql->query("update `newsdata` set `title`='{$news_title}',`content`='{$news_content}' where `id`='{$news_id}'");
			echo "<script>alert('发布成功！');location.href='newslist.php'</script>";
			//_location("set.php",301);
			exit;			
		}
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
			var editor1 = K.create('textarea[name="news_content"]', {
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
            <h1 class="page-title">新闻管理</h1>
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
    <form id="tab" action="editnews.php" method="post">
        <label>新闻标题</label>
        <input type="text" value="<?php echo $row[0]['title']?>" style="width:500px" name="news_title">
		 <input type="hidden" value="<?php echo $id?>" name="news_id">			
        <textarea value="Smith" rows="10" name="news_content" style="width:800px;height:400px;visibility:hidden;"><?php echo $row[0]['content']?></textarea>
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


