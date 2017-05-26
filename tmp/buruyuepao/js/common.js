$(function(){
    $('.result').css('height',$(window).height());
    $(".ruler_btn").click(function(){
        $('.go_ruler').hide();
        $(".ruler").show();
        var uid = $(this).attr('id');
        $.ajax({
                type:'post',
                url:'ajax.php?a=user_info',
                data:{uid:uid},
                success:function(data){
                    if(data.status==1)
                    {
                        if(data.dis>=800 && data.dis<1000)
                        {
                            $('.ruler_result').attr('src','img/ruler_yes_4.png');
                        }
                        else if(data.dis>=1000 && data.dis<1400)
                        {
                            $('.ruler_result').attr('src','img/ruler_yes_3.png');
                        }
                        else if(data.dis>=1400 && data.dis<1600)
                        {
                            $('.ruler_result').attr('src','img/ruler_yes_2.png');
                        }
                        else if(data.dis>=1600)
                        {
                            $('.ruler_result').attr('src','img/ruler_yes_1.png');
                        }
                        else
                        {
                            $('.ruler_result').attr('src','img/ruler_yes_5.png');                         
                        }
                        $('.ruler_dis').text(data.dis);
                    } 
                    else
                    {
                        $('.ruler_result').attr('src','img/ruler_no.png');    
                    }   
                },
                dataType:'json'
            })
    })
    //
    $('.J_score').click(function(){
        $('.info_box').show();
        $('.reslut_box').hide();
    })    
    //
    var REG={
    name:/^[a-zA-Z0-9\u4e00-\u9fa5]{2,12}$/,
    phone:/(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/,
    carno:/^[\u4E00-\u9FA5][a-zA-Z][\da-zA-Z]{5}$/
    }
    $('#J_infosave').click(function(){
        var name = $('#J_name').val();
        var phone = $('#J_phone').val();
        var uid = $('.ruler_btn').attr('id');
        var dis = $('#J_dis').text();
        if(dis=='')
        {
            alert('成绩不能为空');
            return false;
        }
        if(name=='')
        {
            alert('输入姓名');
            return false;
        }
        else if(!REG.name.test(name)){
            alert('姓名格式不正确！');
            return;
        }
        if(phone=='')
        {
            alert('输入手机号码');
            return false;
        }
        else if(!REG.phone.test(phone)){
            alert('手机号码格式不正确！');
            return;
        } 
        $('.saveing').show();
        $.ajax({
            type:'post',
            url:'ajax.php?a=info_save',
            data:{name:name,phone:phone,uid:uid,dis:dis},
            success:function(data){
                //
                if(data.status==1)
                {
                    $('.saveing').hide();
                    $('.info_box').hide(); 
                    $('.success_box').show();  
                }
                else if(data.status==-2)
                {
                    alert('姓名/手机号码为空');
                    $('.saveing').hide();  
                }
                else if(data.status==-3)
                {
                    alert('您已经提交过成绩了');
                    $('.saveing').hide();  
                }
                else if(data.status==-2)
                {
                    alert('姓名/手机号码为空');
                    $('.saveing').hide();  
                }
                else if(data.status==-4)
                {
                    alert('uid为空');
                    $('.saveing').hide();  
                }
                else if(data.status==-5)
                {
                    alert('奖品已经发放完了');
                    $('.saveing').hide();  
                }
                else if(data.status==-1)
                {
                    alert('提交失败'); 
                    $('.saveing').hide(); 
                }
            },
            dataType:'json'
        })
        
         
    })
    
    //首页建筑物移动
    
    $('.building_i').timer({
		delay:10,
		repeat: true,
        autostart:true,
		callback: function(index){
            var j = index*-1;    		      
            $('.building_i').css('background-position',j+'px 0px');
		}
    });
    
    $('.people_i').timer({
		delay:50,
		repeat: true,
		callback: function(index) {
			//console.log(index);
            var j = (index%8)*-100;
			$('.people_i .people_sprit').css('background-position',j+'px 0px');
		}
    });
    
    //
    $('.J_share').click(function(){
        $('.layer').show();
        $('.share_tips').show();
    })
    
    $('.share_tips').click(function(){
        $('.layer').hide();
        $('.share_tips').hide();    
    })
})