<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$id=guolv($_GET['id']);
$aid=guolv($_POST['id']);
if(is_numeric($id)==false){
		echo "<script>alert('广告不存在');location.href='adlist.php'</script>";
		exit;	
}
$row=$mysql->query("select * from `addata` where `id`='{$id}'");

if($_POST && is_numeric($aid)){
		$ad_content=guolv($_POST['ad_content']);
		$mysql->execute("update `addata` set `ad_content`='{$ad_content}' where `id`='{$aid}'");
		echo "<script>alert('修改成功');location.href='edit_ad.php?id={$aid}'</script>";
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
            <h1 class="page-title">广告管理</h1>
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
    <form id="tab" action="edit_ad.php?id=<?php echo $id?>" method="post">
		<input type="hidden" name="id" value="<?php echo $id?>">
		<label><?php echo $row[0]['ad_type']?></label>
        <!--<input type="text" value="" style="width:500px" name="pic">
		<input name="filename" type="file">-->       
		<label></label>		
		<textarea value="Smith" rows="10" name="ad_content" style="width:800px;height:400px;"><?php echo $row[0]['ad_content']?></textarea>
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


