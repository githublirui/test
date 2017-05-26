<?php
require 'function.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>安居客小区采集系统</title>
        <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    </head>
    <ul style="list-style: none;font-size: 12px;color:green" class="add_area_list">
    </ul>
</html>
<script>
    $(document).ready(function() {

        /**
         *  自动刷新添加记录 
         */
        var ajax_record = setInterval("area_add_record()", 2000);
        var area_add_record = function() {
            $.ajax({
                type: 'post',
                url: '<?php echo $base_url ?>/ajax.php?ac=al',
                dataType: 'html',
                success: function(res) {
                    $(".add_area_list").html(res);
                }
            })
        }
    })
</script>