<?php
$ac = @$_GET['ac'];
if ($ac == 'tagauto') {
    $arr = array('1' => '你好', '2' => 'b', 3 => 'dddd');
    echo json_encode($arr);
    die;
}
if ($_POST) {
    var_dump($_POST);
}
?>
<script src="/js/jquery-1.6.4.min.js"></script>
<script src="/js/taginput/jquery.tagsinput.js"></script>
<link rel="stylesheet" type="text/css" href="/js/taginput/jquery.tagsinput.css" />
<form action="#" method="post">
    <input name="tags" id="tags" />
    <input type="button" class="submit"/>
</form>
<script type="text/javascript">
    $(".submit").click(function(){
        $("form").submit();
    })
    $("#tags").tagsInput({
        'autocomplete_url': '/js.php?ac=tagauto',
        //        'autocomplete': { option: value, option: value},
        //        'height':'100px',
        //        'width':'300px',
        //        'interactive':true,
        //        'defaultText':'add a tag',
        //        'onAddTag':callback_function,
        //        'onRemoveTag':callback_function,
        //        'onChange' : callback_function,
        //        'removeWithBackspace' : true,
        //        'minChars' : 0,
        //        'maxChars' : 0, //if not provided there is no limit,
        //        'placeholderColor' : '#666666'
    });
</script>