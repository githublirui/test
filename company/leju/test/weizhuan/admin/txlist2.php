<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$page=$_GET['page'];
if(is_numeric($page)==false){
	$page=1;
}else{
	$page=$page+1;
}

//删除会员
$state2=guolv($_GET['state2']);
if(is_numeric($state2)){
	$mysql->execute("update `txdata` set `state`=2 where `id`='{$state2}'");
	echo "<script>alert('操作成功');location.href='txlist.php'</script>";
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
            <h1 class="page-title">拒绝提现</h1>

<div class="well">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
<div class="pull-right">
<button class="btn"><a href="txlist2.php">首页</a></button>
<button class="btn"><a href="?page=<?php echo $page?>">下一页</a></button>
</div>		  
		<div class="well">
    <table class="table">
      <thead>
        <tr>
		  <th>申请时间</th>
		  <th>用户ID</th> 
		  <th>支付宝</th>
		  <th>提现金额</th>
		  <th>状态</th>
        </tr>
      </thead>
      <tbody>
<?php
$row=$mysql->query("select * from `txdata` where `state`=2 order by `id` desc");
$row_tx=page_array(50,$page,$row,0);
	  foreach($row_tx as $v_tx){
		$time=date("Y-m-d H:i",$v_tx['time']);
print <<<table
        <tr>
		  <td>{$time}</td>
		  <td>{$v_tx['uid']}</td>
		  <td>{$v_tx['alipay']}</td>
		  <td>{$v_tx['money']}</td>
		  <td>
			<font color=red>拒绝支付</font>
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


