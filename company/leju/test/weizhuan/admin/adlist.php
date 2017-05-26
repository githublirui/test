<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
//print_r($config);
//删除广告
if(is_numeric($_GET['del_id'])){
	$del_id=guolv($_GET['del_id']);
	$mysql->query("delete from `addata` where `id`='{$del_id}'");	
	echo "<script>alert('删除成功');location.href='adlist.php'</script>";
	exit;		
}
//发布广告
if($_POST){
	if($_POST['do']=='ad'){
		$ad_content=guolv($_POST['ad_content']);
	}
	if($_POST['do']=='js'){
		$ad_content=guolv($_POST['ad_content_js']);
	}
	$ad_type=guolv($_POST['ad_type']);
	$ad_list=guolv($_POST['ad_list']);
	//$ad_pv=guolv($_POST['ad_pv']);
	$ad_endtime=guolv($_POST['ad_endtime']);
	if($ad_type!=='' && $ad_content!=='' && $ad_endtime!==''){
		// if($ad_pv!=='' && $ad_endtime!==''){
			// echo "<script>alert('点击次数/到期时间，只能二选其一，空着即可');location.href='adlist.php'</script>";
			// exit;			
		// }
		$row=$mysql->query("select * from `addata` where `ad_type`='{$ad_type}' and `ad_list`='{$ad_list}' limit 1");
		if($row){
			echo "<script>alert('广告已经存在请先删除');location.href='adlist.php'</script>";
			exit;			
		}
			$arr=array(
				//'id'=>null,
				'ad_type'=>$ad_type,
				'ad_content'=>$ad_content,
				'ad_list'=>$ad_list,
				'pv'=>$ad_pv,
				'endtime'=>$ad_endtime,
			);
			$value=arr2s($arr);
			$mysql->query("insert into `addata` {$value}");
			echo "<script>alert('增加成功');location.href='adlist.php'</script>";
			exit;
			
	}else{
			echo "<script>alert('请填写每一项');location.href='adlist.php'</script>";
			exit;		
	}
}
?>
	<link rel="stylesheet" href="../editor/themes/default/default.css" />
	<link rel="stylesheet" href="../editor/plugins/code/prettify.css" />
	<script src="static/jquery.js" type="text/javascript"></script> 
	<script charset="utf-8" src="../editor/kindeditor.js"></script>
	<script charset="utf-8" src="../editor/lang/zh_CN.js"></script>
	<script charset="utf-8" src="../editor/plugins/code/prettify.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="ad_content"]', {
				cssPath : '../editor/plugins/code/prettify.css',
				uploadJson : '../editor/php/upload_json.php',
				fileManagerJson : '../editor/php/file_manager_json.php',
				allowFileManager : true,
			});
			prettyPrint();
		});
	</script>
<?php include('head.php')?>    
    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">广告管理</h1>
			
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#ad" data-toggle="tab">广告发布</a></li>
	  <li><a href="#js" data-toggle="tab">JS广告</a></li>
      <li><a href="#list" data-toggle="tab">广告管理</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="ad">
	  <form action="adlist.php" method="post">
	  <input type="hidden" name="do" value="ad">
	    <label>广告类型： </label>
                                <select name="ad_type">
                                    <option value="顶部广告" selected="">顶部广告</option>
									<option value="底部广告">底部广告</option>
                                    <option value="悬浮广告">悬浮广告</option>
									<option value="统计代码">统计代码</option>
                                </select>
		<label>投放区域：</label>
                                <select name="ad_list">
                                    <option value="0全站投放" selected="">全站投放</option>
                                </select>		
	    <label>代码编辑：</label>
        <textarea value="Smith" rows="10" name="ad_content" style="width:700px;height:300px;visibility:hidden;"></textarea>  		
		<!--
		<label>点击次数：</label>
		<input type="text" value="" style="width:200px" name="ad_pv">
		-->
		<label>到期时间：</label>
		<input class="datepicker" type="text" value="" style="width:200px" name="ad_endtime">		
		<label></label>
		<button class="btn btn-info">提交</button>
	</form>	
      </div>
	  
	 <div class="tab-pane fade" id="js">
	  <form action="adlist.php" method="post">
	  <input type="hidden" name="do" value="js">
	    <label>广告类型： </label>
                                <select name="ad_type">
                                    <option value="顶部广告" selected="">顶部广告</option>
									<option value="底部广告">底部广告</option>
                                    <option value="悬浮广告">悬浮广告</option>
									<option value="统计代码">统计代码</option>
                                </select>
		<label>投放区域：</label>
                                <select name="ad_list">
                                    <option value="0全站投放" selected="">全站投放</option>
                                </select>		
	    <label>代码编辑：</label>
        <textarea value="Smith" rows="10" name="ad_content_js" style="width:700px;height:300px"></textarea>  		
		<!--
		<label>点击次数：</label>
		<input type="text" value="" style="width:200px" name="ad_pv">
		-->
		<label>到期时间：</label>
		<input class="datepicker" type="text" value="" style="width:200px" name="ad_endtime">				
		<label></label>
		<button class="btn btn-info">提交</button>
	</form>	
      </div>
	  
       <div class="tab-pane fade" id="list">
		<div class="well">
    <table class="table">
      <thead>
        <tr>
          <th>广告类型</th>
		  <th>投放区域</th>
		  <th>到期时间</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_ad=$mysql->query("select * from `addata`");
foreach($row_ad as $v_ad){
print <<<table
        <tr>
          <td>{$v_ad['ad_type']}</td>
          <td>{$v_ad['ad_list']}</td>
		  <td>{$v_ad['endtime']}</td>
		  <td>
			<a class="btn btn" href="edit_ad.php?id={$v_ad['id']}"><i class="icon-edit"></i></a>
			<a class="btn btn" href="?del_id={$v_ad['id']}"><i class="icon-trash"></i></a>
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

</div></div></div></div>
    
    <footer>
        <hr>
        
        <p class="pull-right"><!--power by right--></p>
        
        
        <p><!--power by --></p>
    </footer>
  

    

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<script src="lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="../static/jquery.js"></script>
<script type="text/javascript" src="../static/jquery.datePicker-min.js"></script>
<link type="text/css" href="../static/datetime.css" rel="stylesheet" />
<script type="text/javascript">
$(document).ready(function(){
	$(".datepicker").datePicker({
		clickInput:true
	});
});
</script>  

  </body>
</html>
</script>  

  </body>
</html>


