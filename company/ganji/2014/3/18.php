<meta charset="utf-8" />
<table style="background: #000000;">
    <?php
    $arr = array();
//var_dump(empty($arr));#true
//var_dump($GLOBALS['argv']);
    $filename = dirname(__FILE__) . '/../801_0';
    $category_arr3 = unserialize(file_get_contents($filename));
    $category_arr3 = $category_arr3['info'][0]['itemList'];
    foreach ($category_arr3 as $category_arr) {
        if ($category_arr['imgUrl']) {
            ?>
            <tr>
                <td style="color: #FFFFFF">
                    <?php echo $category_arr['title']; ?>
                </td>
                <td>
                    <?php echo '<img src="' . $category_arr['imgUrl'] . '" />' ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>