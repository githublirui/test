<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');

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
            <h1 class="page-title">等待提现</h1>
			
<div class="btn-toolbar">
    <a href="outexcel.php" target="_blank" class="btn btn-primary">批量支付导出excel</a>
  <div class="btn-group">
  </div>
</div>

<div class="well">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
		<div class="well">
    <table class="table">
      <thead>
        <tr>
		  <th>申请时间</th>
		  <th>支付宝</th>
		  <th>提现金额</th>
		  <th>操作</th>
        </tr>
      </thead>
      <tbody>
<?php
$row_tx=$mysql->query("select * from `txdata` where `state`=0 order by `id` desc");
	  foreach($row_tx as $v_tx){
		$time=date("Y-m-d H:i",$v_tx['time']);
print <<<table
        <tr>
		  <td>{$time}</td>
		  <td>{$v_tx['alipay']}</td>
		  <td>{$v_tx['money']}</td>
		  <td>
			<a class="btn" href="?state2={$v_tx['id']}">拒绝支付</a>
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


