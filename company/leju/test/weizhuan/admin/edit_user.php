<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
if($_POST){
	$pass=guolv($_POST['pass']);
	$money=guolv($_POST['money']);
	$alipay=guolv($_POST['alipay']);
	$realname=guolv($_POST['realname']);
	$wx=guolv($_POST['wx']);
	$id=guolv($_POST['uid']);
	$wgateid=guolv($_POST['wgateid']);
	$mysql->execute("update `userdata` set `pass`='{$pass}',`money`='{$money}',`alipay`='{$alipay}',`realname`='{$realname}',`wx`='{$wx}',`wgateid`='{$wgateid}' where `id`='{$id}'");
	echo "<script>alert('修改成功');location.href='edit_user.php?uid={$id}'</script>";
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
            <h1 class="page-title">用户修改</h1>
<!--			
<div class="btn-toolbar">
    <a href="#myModal" data-toggle="modal" class="btn">Delete</a>
  <div class="btn-group">
  </div>
</div>
-->
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">设置</a></li>
      <!--<li><a href="#profile" data-toggle="tab">图片上传</a></li>-->
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">	  
    <form id="tab" action="edit_user.php" method="post">
<?php
$uid=guolv($_GET['uid']);
$row=$mysql->query("select * from `userdata` where `id`='{$uid}}' limit 1");
if(!$row){
	_location('user.php',301);
	exit;
}else{
	$data=$row[0];
}
?>
<input type="hidden" name="uid" value="<?php echo $uid?>">
        <label>手机号：</label>
        <input type="text" style="width:300px" name="phone" value="<?php echo $data['phone']?>" readonly>
         <label>密码：</label>
        <input type="text" style="width:300px" name="pass" value="<?php echo $data['pass']?>">
         <label>微信号：</label>
        <input type="text" style="width:300px" name="wx" value="<?php echo $data['wx']?>">		
         <label>账户余额：</label>
        <input type="text" style="width:300px" name="money" value="<?php echo $data['money']?>">
         <label>真实姓名：</label>
        <input type="text" style="width:300px" name="realname" value="<?php echo $data['realname']?>">
         <label>支付宝：</label>
        <input type="text" style="width:300px" name="alipay" value="<?php echo $data['alipay']?>">    	
         <label>微信识别码</label>
        <input type="text" style="width:300px" name="wgateid" value="<?php echo $data['wgateid']?>">    	
		 		 
		 <label></label>
		 <button class="btn btn-primary"><i class="icon-save"></i> 修改</button>
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


