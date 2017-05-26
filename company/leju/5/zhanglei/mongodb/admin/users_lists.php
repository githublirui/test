<meta charset='utf-8' />
<style>
table th {
	text-align: left;
	padding-left: 10px;
}
table td {
	text-align: left;
	padding-left: 10px;
}
a {
	text-decoration: none;
}
</style>
<?php if(!empty($users_lists)): ?>
<table border=1 style='border-collapse: collapse; width: 500px'>
	<tr>
		<th>姓名</th>
		<th>邮箱</th>
		<th>性别</th>
		<th>时间</th>
	</tr>
	<?php foreach($users_lists as $key => $value): ?>
	<tr>
		<td><?php echo isset($value['username']) ? $value['username'] : ''; ?></td>
		<td><?php echo isset($value['email']) ? $value['email'] : ''; ?></td>
		<td><?php echo isset($value['sex']) ? $value['sex'] : ''; ?></td>
		<td><?php echo isset($value['created_time']) ? $value['created_time'] : ''; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>