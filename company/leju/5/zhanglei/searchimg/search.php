<meta charset='utf8' />
<?php
if(isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){

    if(file_exists('../autoload/loader.class.php')){
        require_once('../autoload/loader.class.php');
    }else{
        throw new Exception('loader.class.php is not exists');
    }

    spl_autoload_register(array('loader', 'loadClass'));

    $Upload = Upload::getInstance();
    $ImagesHash = ImagesHash::getInstance();

    $conf = array(
        'host' => 'localhost',
        'user' => 'root',
        'pass' => 'root',
        'name' => 'demo',
        'port' => '3306'
    );
    $_name = 'images';
    $Db = Db::getInstance($conf);

    /* 先查看有没有相似的图片, 不管有没有, 将用户上传的图片提交到数据库 */

    $image_info = $Upload->uploadFile('imgkeyword');
    if($image_info['code'] !== 0){
        throw new Exception('上传图片错误');
    }
    $path = $image_info['path'];
    $hash = $ImagesHash->getImagesHash($path);
    $data = array(
        'path' => $path,
        'hash' => $hash
    );
    
    // 拿用户刚刚上传的图片的hash值, 去数据库匹配, 匹配到了, 返回
    $lists = array();
    $field = '*';
    $total_lists = $Db->fetchAll($_name, $field);
    if(!empty($total_lists)){
        foreach($total_lists as $image){
            if($ImagesHash->checkIsSimilarImg($hash, $image['hash'])){
                $lists[] = $image;
            }
        }
    }
    
    // 将用户上传的图片insert去数据库
    if(!$Db->insert($_name, $data)){
        throw new Exception('添加到数据库失败');
    }

}
?>
<form action='search.php' method='post' enctype="multipart/form-data">
    上传图片: <input name='imgkeyword' type='file' />
    <br /><br />
    <input name='submit' value='上传' type='submit' />
</form>


<?php if(isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'): ?>

<?php if(isset($lists) && !empty($lists)): ?>
<?php foreach($lists as $list): ?>
<div>
    <img src="<?php echo $list['path']; ?>" />
</div>
<?php endforeach; ?>
<?php else: ?>
<div style='color: red; font-weight: bolder; font-size: 14'>
    未匹配到图片
</div>
<?php endif; ?>

<?php endif; ?>