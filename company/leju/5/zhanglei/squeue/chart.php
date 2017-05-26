<script src='../../demo/jquery/jquery-1.5.1.min.js'></script>
<script>
$(function(){
    var refresh = true;
    $('textarea[name=content]').focus();
    $('input[name=button]').click(function(){
        var content = $('textarea[name=content]').val();
        if(!content){
            alert('please input you words');
            $('textarea[name=content]').focus();
        }else{
            $.ajax({
                type : 'post',
                url  : './memcache_squeue.php',
                dataType : 'json',
                data : 'content='+content+'&refresh='+refresh,
                success: function(result){
                    if(result.status == 0){
                        alert(result['message']);return false;
                    }else if(result.status == 1){
                        alert(result['message']);return false;
                    }else if(result.status == 2){
                        var html = '';
                        for(var i=0; i<result.data.length; i++){
                            html += "<div style='text-align:left;margin-left:20px;height:40px;border-bottom:1px solid red;margin-top:10px;'>";
                            html += "<span style='font-size:16px; color:red'>" + result.data[i].ip + " say : </span><br />";
                            html += "<span style='margin-left:20px;color:blue;font-size:14px;'>" + result.data[i].content + "</span>";
                            html += "</div>";
                        }
                        if(html) $('.history').html(html);
                    }else{
                        alert('system error');return false;
                    }
                }
            });
        }
        refresh = false;
        $('textarea[name=content]').val('');
        $('textarea[name=content]').focus();
    })
});
</script>
<meta charset='utf-8' />
<body style='text-align:center;'>
<div style='text-align:center;border:5px solid blue;width:800px;height:500px;'>
    <div style='height:350px' class='history'></div>
    <div style='border-top:2px solid blue;width:760px;height:130px;padding:20px;'>
        <div>
            <textarea style='border:0;width:740px;height:90px;font-size:14px;' name='content'></textarea>
        </div>
        <div>
            <input style='float:right;margin-bottom:10px;' name='button' value='发送' type='button' />
        </div>
    </div>
</div>
</body>