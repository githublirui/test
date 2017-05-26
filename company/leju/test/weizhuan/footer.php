<?php
@session_start();
if(isset($_SESSION)){
	$_uid=$_SESSION['userdata']['id'];
}else{
	$_uid=guolv($_GET['uid']);
}
?>
<div class="positionFooter">
	<ul>
		<li><a class="a1" href="<?php echo $site?>/ucenter.php">个人中心</a></li>   
		<li>
        					<a class="a2" href="<?php echo $site?>/list.php?uid=<?php echo $_uid?>">开始赚钱</a>
			        </li>
		<li>
        		<a class="a3" href="apprent.php">我要收徒</a>
		        </li>
	</ul>
</div>

<?php
$mysql->__destruct();
$mysql->close();
?>


