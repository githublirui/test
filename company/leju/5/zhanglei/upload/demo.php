<?php
if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
    if(file_exists('./upload.class.php')) include_once('./upload.class.php');
    $upload = Upload::getInstance();
    // 支持多个附件上传 , 返回 第key张图片 => 所有信息
    $file = $upload->uploadFile('file');
    /*
    Array
    (
        [1] => Array
            (
                [message] => 第1个附件上传成功
                [code] => 0
                [filename] => 2013-01-10/1357788566adele1.jpg
                [path] => ./upload/20130110/1357788566adele1.jpg
            )

        [2] => Array
            (
                [message] => 第2个附件上传成功
                [code] => 0
                [filename] => 2013-01-10/1357788566adele2.jpg
                [path] => ./upload/20130110/1357788566adele2.jpg
            )

    )
    */
    //print_r($file);die;
    // 单一图片的缩略图以及水印
    $thumb = $upload->getThumbnail($file[1]['path'], 300, 300);
    $mark = $upload->getWaterMark($file[1]['path'], 'zhanglei love adele', array(32, 'asdf', 'asdf'));
}
?>
<script src='../../demo/jquery/jquery-1.5.1.min.js'></script>
<script>
function _clone(){
    var html = $('div').children().clone();
    $('div').after(html);
}
</script>
<form method = 'post' action = 'demo.php' enctype="multipart/form-data">
    <div>
        <input name='file[]' type='file' />
        <br /><br />
    </div>
    <span onclick='_clone()'>clone</span>
    <input name='submit' type='submit' value='submit' />
</form>