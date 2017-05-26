<style>
#page{font:12px/16px arial;}
#page span{float:left;margin:0px 3px;}
#page a{float:left;margin:0 3px;border:1px solid #ddd;padding:3px 7px; text-decoration:none;color:#666}
#page a.now_page,#page a:hover{color:#fff;background:#05c}
</style>
<?php
if (!file_exists('../autoload/loader.class.php'))
{
	throw new Exception('autoload class is not exists');
}
else
{
    include_once('../autoload/loader.class.php');
}

if (!function_exists('spl_autoload_register'))
{
	throw new Exception('function spl_autoload_register is not exists');
}

try {
	spl_autoload_register(array('loader', 'loadClass'));
} catch (Exception $e) {
	throw new Exception($e->getMessage());
}


$data = array(
    'total_rows'    => 100,
    'list_rows'     => 5,
    'page_name'     => 'page'
);

$page       = new Pager($data);
$page_links = Pager::show();

?>
<div id='page'>
<?php echo $page_links; ?>
</div>