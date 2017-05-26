<div style="margin: 10 0 10 0">
	<a href='bottle_add.php'>添加漂流瓶类型</a>
</div>

<?php if(!empty($bottle_categories_lists)): ?>
<table border=1 style='border-collapse: collapse; width: 500px'>
	<tr>
		<th>漂流瓶名称</th>
		<th>漂流瓶描述</th>
	</tr>
	<?php foreach($bottle_categories_lists as $key => $value): ?>
	<tr>
		<td><?php echo isset($value['name']) ? $value['name'] : ''; ?></td>
		<td><?php echo isset($value['description']) ? $value['description'] : ''; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>