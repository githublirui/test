<?php

$act = isset($_GET['act']) ? $_GET['act'] : '';

if($act == 's'){

if(file_exists("./sphinx.class.php")) include_once("./sphinx.class.php");
if(file_exists("../mysql/DBHelper.class.php")) include_once("../mysql/DBHelper.class.php");

$conf = array(
    'host' => '127.0.0.1',
    'port' => 9312
);

$db_conf = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'root',
    'db'   => 'demo'
);
$db = DBHelper::getInstance($db_conf);

$_keyword = $_GET['keyword'];
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$start = ($page - 1) * $limit;
$indexer = "news news_delta";
$main_index = "news";

try{
    $cl = Sphinx::getInstance($conf);
    $cl->sphinxClient->SetMatchMode(SPH_MATCH_ANY);
    $cl->sphinxClient->SetSortMode(SPH_SORT_EXTENDED, "@weight desc created_time desc");
    $results = $cl->Query($keyword, $indexer, $start, $limit);
    $words = $cl->getWords($results);
    if($results === false){
        echo $cl->getLastError();
    }else{
        if($cl->GetLastWarning()){
            echo $cl->GetLastWarning();
        }
    }
    $lists = array();
    $opts = array(
        'before_match'      => '<b style="color:red">',
        'after_match'       => '</b>',
        'exact_phrase'      => false
    );
    $lists = array();
    foreach($results['matches'] as $result){
        $id = $result['id'];
        $data = $db->readOne("news", array("id" => $id), '*', false, false);
        $docs = array($data['title'], $data['summary']);
        $tmp = $cl->sphinxClient->BuildExcerpts($docs, $main_index, $words, $opts);
        if(!$tmp) echo $cl->getLastError();
        $data['title'] = $tmp[0];
        $data['summary'] = $tmp[1];
        $lists[] = $data;
    }
}catch(Exception $e){
    echo $e->getMessage();
}
?>
<div>
    <form method='get' action='test_coreseek.php'>
        <input name='act' value='s' type='hidden' />
        <input name='keyword' type='text' style='width:320px; height:30px;' value='<?php echo isset($keyword) ? $keyword : ""; ?>' />
        <input name='submit' value='百度一下' type='submit' style='height:30px;' />
    </form>
</div>
<div style='margin-top:20px;'>
    <?php if($lists): ?>
    <?php foreach($lists as $list): ?>
    <div style='margin-top:20px;'>
        <div>
            <a href='javascript:void()'><?php echo $list['title']; ?></a>
            <span style='margin-left:20px; font-size:14px; color:green'><?php echo $list['created_time']; ?></span>
        </div>
        <div style='font-size:14px;'><?php echo $list['summary']; ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
}else{
?>
<div style='text-align:center;margin-top:200px;'>
    <form method='get' action='test_coreseek.php'>
        <input name='act' value='s' type='hidden' />
        <input name='keyword' type='text' style='width:320px; height:30px;' />
        <input name='submit' value='百度一下' type='submit' style='height:30px;' />
    </form>
</div>
<?php
}
?>