<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');

//删除
$del_id=guolv($_GET['del_id']);
if(is_numeric($del_id)){
	$mysql->query("delete from `newsdata` where `id`='{$del_id}'");
	echo "<script>alert('删除成功');location.href='newslist.php'</script>";
}
?>

<?php include('head.php')?>    

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
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
		<div class="well">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>标题</th>
		  <th>操作</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_news=$mysql->query("select * from `newsdata` order by `id` desc");
	  foreach($row_news as $v_news){
print <<<table
        <tr>
          <td>{$v_news['id']}</td>
          <td><a target="_blank" href="{$site}/news.php?id={$v_news['id']}">{$v_news['title']}</a></td>
		  <td>
		    <a class="btn btn" target="_blank" href="editnews.php?id={$v_news['id']}">修改</a>
			<a class="btn btn" href="?del_id={$v_news['id']}">删除</a>
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


