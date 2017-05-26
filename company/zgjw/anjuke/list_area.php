<?php

include 'conn.php';
include 'function.php';
include '../3rd/paginator/paginator.class.php';
?>
<!DOCTYPE html PUBLIC “-//W3C//DTD XHTML 1.0 Transitional//EN”
    “http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd”>
<html xmlns=”http://www.w3.org/1999/xhtml”>
    <head>
        <meta http-equiv=”Content-Type” content=”text/html; charset='gbk'/>
        <title>小区列表</title>
        <style type="text/css">
            .paginate {
                font-family: Arial, Helvetica, sans-serif;
                font-size: .7em;
            }
            a.paginate {
                border: 1px solid #000080;
                padding: 2px 6px 2px 6px;
                text-decoration: none;
                color: #000080;
            }
            a.paginate:hover {
                background-color: #000080;
                color: #FFF;
                text-decoration: underline;
            }
            a.current {
                border: 1px solid #000080;
                font: bold .7em Arial,Helvetica,sans-serif;
                padding: 2px 6px 2px 6px;
                cursor: default;
                background:#000080;
                color: #FFF;
                text-decoration: none;
            }
            span.inactive {
                border: 1px solid #999;
                font-family: Arial, Helvetica, sans-serif;
                font-size: .7em;
                padding: 2px 6px 2px 6px;
                color: #999;
                cursor: default;
            }
        </style>
    </head>
    <body>
        <?php
        /*         * ********************************** */
        /*          分页类使用                */
        /*         * ********************************** */
        #1.获取总条数
        $count_sql = "select count(*) as num from tbl_content";
        $count = mysql_fetch_assoc(mysql_query($count_sql));
        $count_num = $count['num'];

        $pages = new Paginator(); #实例化class
        $pages->items_total = $count_num; #总条数
        $pages->mid_range = 5; #相隔的条数会出现省略1-10....
        $pages->default_ipp = 10; #默认一页显示条数
        $pages->paginate(); #初始化
        #查询
        $select_data = "select id,name,address,urltext,username,inputtime from tbl_content order by id desc" . $pages->getLimitSql();
        $resouce = mysql_query($select_data);

		$count_sql = "select count(*) as num  from tbl_content";
		$count_tbl_content =  mysql_fetch_assoc(mysql_query($count_sql));
        ?>
        <table>
			<tr>
				<td colspan="5">总数: <?php echo  $count_tbl_content['num'] ?></td>
			</tr>
            <tr>
                <th width="10%">编号</th>
                <th width="25%">小区名</th>
                <th width="30%">小区地址</th>
                <th width="20%">录入人员</th>
                <th width="15%">录入时间</th>
            </tr>
            <?php
            #循环显示数据
            while ($row = mysql_fetch_assoc($resouce)) {
                ?>
                <tr>
				    <td align="center"><?php echo $row['id'] ?></td>
                    <td><a href="<?php echo $row['urltext'] ?>" target="_blank"><?php echo Utf8ToGbk($row['name']) ?></a></td>
                    <td><?php echo Utf8ToGbk($row['address']) ?></td>
                    <td><?php echo Utf8ToGbk($row['username']) ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$row['inputtime']) ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        #显示分页
        echo $pages->display_pages(); #显示分页条数
        //echo $pages->display_items_per_page(); #显示选择每页显示条数的选择框
       echo $pages->display_jump_menu(); #显示跳转页数的选择框
        ?>
    </body>

</html>