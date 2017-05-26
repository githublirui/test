<script src='../javascript/jquery-2.1.0.min.js'></script>

<script>
$(function(){
	$('.operation a').click(function(){
		$('#divprogressbar').css('display', 'block');
		$.ajax({
			data: '',
			dataType: 'json',
			type: 'post',
			url: 'pop.php',
			success: function(response){
				if(response.status === 1){
					$('#divprogressbar img').css('display', 'none');
				}
			}
		});
	});
});
</script>

<style>
.operation {
	margin-top: 10px;
}
.operation a {
	text-decoration: none;
}
</style>

<div class='operation'>
	<a href='javascript:void(0)'>push</a>
</div>

<div class='operation'>
	<a href='javascript:void(0)'>pop</a>
</div>

<div style='text-align: center; margin-top: 300px; display: none' id='divprogressbar'>
	<img src='load.gif' />
</div>