<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>测试定位</title>
	<script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>
</head>
<body>
 <input type="button"  class="getxy" value="获取" />

 <script type="text/javascript">

	$(document).ready( function() {
		$(".getxy").click( function() {
			 $.ajax({
				type:'get',
				url:'/tmp/test.php',
                success: function(res) {
					alert(res);
				}
			 
			 })
			
		})


	})
 </script>


</body>
</html>

116.31190042694,39.972229896702