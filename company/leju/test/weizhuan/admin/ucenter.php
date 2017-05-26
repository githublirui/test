<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
$rows=$mysql->query("select * from `sitedata` where `uid`='{$userdata['id']}'");

?>

<?php include('head.php')?>    


    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
<div class="well">
    <div id="myTabContent" class="tab-content">
	<div class="row">
		<div class="span5" style="margin-left:40px">
	 
		</div>
		<div class="span4">
	 
		 
		</div>
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


