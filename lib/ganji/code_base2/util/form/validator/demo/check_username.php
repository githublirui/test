<?PHP

header('Content-Type:text/javascript; charset=UTF-8');

sleep( 1 );
$data = !in_array($_POST['username'], array('admin', 'manager', 'webmaster'));

//echo json_encode($data);

if ($data == true){
	echo json_encode($data);
}
else {
    echo json_encode('靠，不要冒充管理员。');
}
?>