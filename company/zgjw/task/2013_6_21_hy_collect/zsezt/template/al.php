<?php
require 'function.php';
$arrs = $_SESSION['add_hy'];
//$time = "1365836096";
//$time_d = time() - $time;
//for($i=0;$i<$time_d;$i++){
//    $arrs[] = '你好';
//}
if (count($arrs) > 0) {
    foreach ($arrs as $k => $arr) {
        ?>
        <li><?php echo $k + 1 ?>.&nbsp&nbsp<?php echo $arr ?>&nbsp&nbsp添加成功</li>
        <?php
    }
} else {
    ?>
    <li style="color:red;">未采集到任何会员，确保链接地址填写正确，或者该区域的会员已经采集完</li>
    <?php } ?>



