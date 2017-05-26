<script>
    //防盗链
    function showImg(url) {
        var frameid = 'frameimg' + Math.random();
        window.img = '<img id="img" src=\'' + url + '?' + Math.random() + '\' /><script>window.onload = function() { parent.document.getElementById(\'' + frameid + '\').height = document.getElementById(\'img\').height+\'px\'; }<' + '/script>';
        document.write('<iframe id="' + frameid + '" src="javascript:parent.img;" frameBorder="0" scrolling="no" width="100%"></iframe>');
    }
</script>
<a href="http://"></a>
<script>
    showImg('http://mmbiz.qpic.cn/mmbiz/HB2yb2MvHKibEWJdm9nibZyHyYoFXkuDPowxOfjMzou1odoJ4Oia201XGR9CBHcDSmKDbxMrGEIn4Hdiczj5GCExcg/0');
</script>