<script type="text/javascript">
    var $thumb_width = 180; //缩略图大小 
    var $thumb_height = 180;
    function preview(img, selection) {
        var scaleX = $thumb_width / selection.width;
        var scaleY = $thumb_height / selection.height;
        var scaleX2 = 100 / selection.width;
        var scaleY2 = 100 / selection.height;
        var scaleX3 = 60 / selection.width;
        var scaleY3 = 60 / selection.height;
        var scaleX4 = 30 / selection.width;
        var scaleY4 = 30 / selection.height;

        $('#avatar180 img').css({
            width: Math.round(scaleX * 300) + 'px', //获取图像的实际宽度 
            height: Math.round(scaleY * 300) + 'px', //获取图像的实际高度 
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
        });
        $('#avatar100 img').css({
            width: Math.round(scaleX2 * 300) + 'px', //获取图像的实际宽度 
            height: Math.round(scaleY2 * 300) + 'px', //获取图像的实际高度 
            marginLeft: '-' + Math.round(scaleX2 * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY2 * selection.y1) + 'px'
        });
        $('#avatar60 img').css({
            width: Math.round(scaleX3 * 300) + 'px', //获取图像的实际宽度 
            height: Math.round(scaleY3 * 300) + 'px', //获取图像的实际高度 
            marginLeft: '-' + Math.round(scaleX3 * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY3 * selection.y1) + 'px'
        });
        $('#avatar30 img').css({
            width: Math.round(scaleX4 * 300) + 'px', //获取图像的实际宽度 
            height: Math.round(scaleY4 * 300) + 'px', //获取图像的实际高度 
            marginLeft: '-' + Math.round(scaleX4 * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY4 * selection.y1) + 'px'
        });
        $('#x1').val(selection.x1);
        $('#y1').val(selection.y1);
        $('#x2').val(selection.x2);
        $('#y2').val(selection.y2);
        $('#w').val(selection.width);
        $('#h').val(selection.height);
    }
    $(document).ready(function () {
        $('#save_thumb').click(function () {
            var x1 = $('#x1').val();
            var y1 = $('#y1').val();
            var x2 = $('#x2').val();
            var y2 = $('#y2').val();
            var w = $('#w').val();
            var h = $('#h').val();
            var jyduploadfile = $('#jyduploadfile').val();
            if (x1 == "" || y1 == "" || x2 == "" || y2 == "" || w == "" || h == "" || jyduploadfile == "") {
                alert("请先选择上传图片");
                return false;
            } else {
                return true;
            }
        });
    });
    $(window).load(function () {
        $('#picView').imgAreaSelect({selectionColor: 'blue', x1: 60, y1: 60, x2: 240,
            maxWidth: 300, minWidth: 100, y2: 240, minHeight: 100, maxHeight: 300,
            selectionOpacity: 0.2, aspectRatio: '1:' + ($thumb_height / $thumb_width) + '', onSelectChange: preview});
    });
</script> 