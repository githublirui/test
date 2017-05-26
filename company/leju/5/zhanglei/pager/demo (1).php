<style>
a{
	text-decoration: none;
	margin-left: 5px;
	margin-right: 5px;
}
.current_page{
	color: red;
}
</style>
<?php
$act = isset($_GET['act']) ? $_GET['act'] : false;

$conf = array(
	'host' => 'localhost',
	'user' => 'root',
	'pass' => 'root',
	'port' => '3306',
	'db' => 'demo'
);

if(file_exists('./Content2Pager.class.php')){
	include_once('./Content2Pager.class.php');
}else{
	throw new Exception('content2pager.class.php is not exists');
}

$Content2Pager = Content2Pager::getInstance('demo.php?act=show&newsid={news_id}', '@@cut@@');

$DBHelper = new mysqli($conf['host'], $conf['user'], $conf['pass'], $conf['db']);

if($act == 'submit'){
	$title = htmlspecialchars(addslashes($_POST['title']));
	$content = htmlspecialchars(addslashes($_POST['content']));
	$returns = $Content2Pager->contentToPager($content);
	//print_r($returns);die;

	/* mysql transaction begin */
	$DBHelper->query("set autocommit = 0");
	$news_insert_sql = sprintf("insert into news set title = '%s', content = '%s'", $title, $content);
    $news_result = $DBHelper->query($news_insert_sql);
	$news_content_page = true;
    $news_id = $DBHelper->insert_id;
    
	if(!empty($returns)){
		foreach($returns as $key => $content_page){
			$news_content_page_sql = sprintf("insert into news_content set news_id = %u, page_num = %u, content = '%s', pager = '%s'",
            $news_id, $key, $content_page['content'], $content_page['pager']);
            //echo $news_content_page_sql;die;
            $news_content_page_result = $DBHelper->query($news_content_page_sql);
            if(!$news_content_page_result){
                $news_content_page = false;
                break;
            }
		}
	}else{
		$news_content_page = false;
	}
    
    if($news_result && $news_content_page){
        $DBHelper->query("commit");
    }else{
        $DBHelper->query("rollback");
    }
	/* mysql transaction end */
    
    header("location: demo.php?act=list");
}elseif($act == 'show'){
    $news_id = intval($_GET['newsid']);
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $sql = sprintf("select * from news_content where news_id = %u and page_num = %u", $news_id, $page);
    $result = $DBHelper->query($sql);
    $news = $result->fetch_array(MYSQLI_ASSOC);
?>
<div>
    <?php echo $news['content']; ?>
</div>
<div>
    <?php echo preg_replace("/{news_id}/", $news_id, htmlspecialchars_decode($news['pager'])); ?>
</div>
<?php
}elseif($act == 'list'){    
    $sql = "select * from news";
    $result = $DBHelper->query($sql);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
?>
<?php foreach(array($rows) as $news): ?>
<div><a href="?act=show&newsid=<?php echo $news['id']; ?>"><?php echo $news['title']; ?></a></div>
<?php endforeach; ?>
<?php
}else{

?>
<form method='post' action='?act=submit'>
	title: &nbsp;&nbsp;<input name='title' type='text' />
	<br /><br />
	content: <textarea cols=60 rows=8 name='content'></textarea>
	<br /><br />
	<input name='submit' type='submit' value='submit' />
</form>
<?php
}
?>