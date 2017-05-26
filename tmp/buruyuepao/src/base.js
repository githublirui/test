var _Conf={};
var REG={
	    name:/^[a-zA-Z0-9\u4e00-\u9fa5]{2,12}$/,
	    phone:/(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/,
	    isIDCard1:/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/,
	    //身份证正则表达式(18位) 
	    isIDCard2:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/ ,
	    isIDCard3:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/,
	    isIDCard4:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|x)$/
	  }


/* -- PRE-LOAD -- */
function _PreLoadImg(b,e){var c=0,a={},d=0;for(src in b){d++}for(src in b){a[src]=new Image();a[src].onload=function(){if(++c>=d){e(a)}};a[src].src=b[src]}};

var gameMain = {};
var loadImgend=false;
var loadGameend=false;
$(function(){
	$('.loader').height($(window).height());   
    _PreLoadImg(["res/css/bg.jpg",
                 "res/css/close.png",
                 "res/css/fatty.png",
                 "res/css/gift.png",
                 "res/css/gift_1.png",
                 "res/css/gold_score.png",
                 "res/css/info.png",
                 "res/css/more.png",
                 "res/css/timercounter_01.png",
                 "res/css/timercounter_02.png",
                 "res/css/timercounter_03.png",
                 "res/css/timercounter_go.png",
                 "res/css/top-bg.png",
                 "http://cdn.w-i-t.cn/13_qdvanke1212/img/img-guide.png"],function(){
    		gameMain.loaderImgEnd();
	});
  

    //游戏说明窗口
     gameMain = {
            init:function(){
                gameMain_self = this;
                $("body").height($(window).height());
                
                
                $(".go_btn").click(function(){
                    $(".index").addClass("hidden");
                    $("#Cocos2dGameContainer").removeClass("hidden");
                    $("#J_gameProp").removeClass("hidden");
                    gameMain_self.play(false);
                });
                $(".J_replay").click(function(){
                    $(".result").hide();
                    $(".success_box").hide();
                    gameMain_self.play(false);
                });
                                
            },
            openwin:function () {
            	alert('win');
            },
            openlose:function () {
            	var score = conf_.distance;
            	$('.result').show();
            	$('.reslut_box').show();
                if(score<800)
                {
                    $('#J_txt').text('还差一点儿就可以领取奖品了。快分享到朋友圈约小伙伴一起跑起来吧！');
                    $('.reslut_title_1').show();
                    $('.reslut_title_2').hide();
                    $('.result_2').find('.J_share').show();
                    $('.result_2').find('.J_score').hide();
                }
                else
                {
                    if(score>=800 && score<1000)
                    {
                        $('#J_txt').text('真不愧是运动达人！提交成绩，你可以获得纪念荧光手环+夜光鞋带。赶快行动吧！');
                    }
                    else if(score>=1000 && score<1400)
                    {
                        $('#J_txt').text('真不愧是运动达人！提交成绩，你可以获得纪念T恤+荧光手环。赶快行动吧！');
                    } 
                    else if(score>=1400 && score<1600)
                    {
                        $('#J_txt').text('真不愧是运动达人！提交成绩，你可以获得纪念T恤+荧光手环+夜光鞋带。赶快行动吧！');
                    } 
                    else if(score>=1600)
                    {
                        $('#J_txt').text('真不愧是运动达人！提交成绩，你可以获得纪念T恤+运动水壶+荧光手环+夜光鞋带。赶快行动吧！');
                    }
                    $('.result_2').find('.J_share').hide();
                    $('.result_2').find('.J_score').show();                    
                    $('.reslut_title_1').hide();
                    $('.reslut_title_2').show();
                }                                  
                $('#J_dis').text(score);
                
            },setDistance:function(current_distance_score){
                 $("#J_gameProp .distance_score b").html(current_distance_score);
            },play:function(isCoutinue){
                 gameLayer.reStartGame(isCoutinue);
                 /******************************************************************************/
                 $("#J_timercounter").removeClass("hidden").removeClass("timercountergo").addClass("timercounter3");
                 setTimeout(function(){
                     $("#J_timercounter").removeClass("timercounter3").addClass("timercounter2");
                 },1000);
                 setTimeout(function(){
                     $("#J_timercounter").removeClass("timercounter2").addClass("timercounter1");
                 },2000);
                 setTimeout(function(){
                     $("#J_timercounter").removeClass("timercounter1").addClass("timercountergo");
                 },3000);
                 setTimeout(function(){
                	 //开始画面 并且隐藏倒计时
                     conf_.isStop=false;
                     $("#J_timercounter").addClass("hidden");
                     gameLayer.openProtcet();
                 },4000);
                 /******************************************************************************/
            },coutinuePlay:function(){
                 gameMain_self.play(true); 
            },loaderEnd:function(){
            	//
            	 loadGameend=true;
            	 if(loadImgend){
	                 $('.loader').remove();
	                 $('.index').removeClass("hidden");
            	 }
            },loaderImgEnd:function(){
            	//
            	loadImgend=true;
           	 if(loadGameend){
	                 $('.loader').remove();
	                 $('.index').removeClass("hidden");
           	 }
           }
        }
    gameMain.init();
})