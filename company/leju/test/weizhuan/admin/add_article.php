<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
if($_POST){
	//新闻发布
		$title=guolv($_POST['title']);
		$content=guolv($_POST['content']);
		$pv_max=guolv($_POST['pv_max']);//浏览次数
		$type_arr=explode('#',guolv($_POST['type']));
		$type=$type_arr[0];
		$money=$type_arr[1];
		$pic=guolv($_POST['pic']);
		$day=date("Y-m-d",time());
		if($title!=='' && $content!==''){
			$mysql->query("insert into `article` values(null,0,'{$title}','{$content}','{$pic}','{$type}',0,'{$pv_max}','{$money}','{$day}')");
			$id=mysql_insert_id();
			if($id!==0){
				echo "<script>alert('发布成功！');location.href='all_article.php'</script>";
			}
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
			var editor1 = K.create('textarea[name="content"]', {
				cssPath : '../editor/plugins/code/prettify.css',
				uploadJson : '../editor/php/upload_json.php',
				fileManagerJson : '../editor/php/file_manager_json.php',
				allowFileManager : true,
			});
			prettyPrint();
		});
	</script>
<script>
			KindEditor.ready(function(K) {
				var uploadbutton = K.uploadbutton({
					button : K('#uploadButton')[0],
					fieldName : 'imgFile',
					url : '../../editor/php/upload_json.php?dir=image',
					afterUpload : function(data) {
						if (data.error === 0) {
							var url = K.formatUrl(data.url, 'absolute');
							K('#img').val(url);
                            $.post("../../editor/php/dunling.php",{
                            url:url
                            },function(slta,sltb){
                            //****//
                            });
						} else {
							alert(data.message);
						}
					},
					afterError : function(str) {
						alert('自定义错误信息: ' + str);
					}
				});
				uploadbutton.fileBox.change(function(e) {
					uploadbutton.submit();
				});
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
      <li><a href="weixin.php">微信文章导入</a></li>
	  <li><a href="http://www.gouso.com" target="_blank">火车头采集</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">	  
    <form id="tab" action="add_article.php" method="post">
        <label>标题</label>
        <input type="text" value="" style="width:500px" name="title">
		<label>缩略图</label>
		<input style="width: 250px;" type="text" id="img" value="" name="pic"/>
		<input type="button" id="uploadButton" value="选择图片"/>
         <label>浏览次数(-1，表示无限)</label>
        <input type="text" value="-1" style="width:200px" name="pv_max">				
         <label>分类</label>
		<select name="type">
<?php
$row_type=$mysql->query("select * from `typedata` order by `id` desc");
foreach($row_type as $v_type){
	echo "<option value=\"{$v_type['id']}#{$v_type['type_pp']}\">{$v_type['name']}(单价：{$v_type['type_pp']})</option>";
}
?>
		</select>            
		<textarea value="Smith" rows="10" name="content" style="width:800px;height:400px;visibility:hidden;"></textarea>
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


