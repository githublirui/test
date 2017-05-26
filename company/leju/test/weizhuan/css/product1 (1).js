var twidth;//边框宽
var theight;//边框长
var imgWidth;//图片宽
var imgHeight;//图片长


$(document).ready(function(){
	$("#showmore").click(function(){
		$("#lxlisthide").toggle(500);							  
	});	
});
function imgOnload(zdt){
	var imgWidth;//图片宽
	var imgHeight;//图片长
	var ptx1,ptx2,pty1,pty2;
	
	var twidth = document.body.clientWidth;
	var theight = parseInt(twidth*4/9);	
	
	$("#mainImg2").height(theight);	
	
	var ig = new Image();
	ig.src = zdt.src;	
	
	if(ig.width/ig.height>9/4){
		imgWidth = parseInt(theight/ig.height*ig.width);
		imgHeight = theight;
		pty1 = 0;
		pty2 = theight;
		ptx1 = 0;
		ptx2 = twidth;	
	}else{
		imgWidth = twidth;
		imgHeight = parseInt(twidth / ig.width * ig.height);
		pty1 = 0;
		pty2 = theight;
		ptx1 = 0;
		ptx2 = twidth;
	}
	var mainimgstyle = "position:absolute;width:" + twidth + "px;height:" + theight + "px;clip:rect(" + pty1 + "px " + ptx2 + "px " + pty2 + "px " + ptx1 + "px)"
	//alert(mainimgstyle);
	$("#mainImgImg").width(imgWidth);//设置边框大小
	$("#mainImgImg").height(imgHeight);
	$("#mainImg").attr("style",mainimgstyle);
}

/*滚动事件
*/


function getQueryString(name){
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r!= null) return unescape(r[2]); 
	return null;    
}

var bodyBottomToTop;//底部条的位置
var waitToTop;//等待条位置当bodyBottomToTop
var isLoading = false;//是否正在加载中
var contentId;//本次加截到的ID编号，当为空时从头加载
var contentLx = getQueryString("lx");//文章类型；
var ctRefid = getQueryString("refid");
var contenStr;//格式文档


function getContent(){
	
	isLoading = true;//状态调整为加截中
	$("#imgbottom").css({"display":"block"});//显示加载图标
	$.ajax({
		url:"/mob/productAPI.asp?id=" + contentId + "&lx=" + contentLx,
		dataType:"xml", 
		success:function(data){
			contenStr = $("#tmaintemp").html();
			$(data).find("clist").each(function(){ 
				var clist = $(this); 
				contentId = clist.attr("id"); 
				var fcTitle = clist.attr("title");
				if(fcTitle.length>16){fcTitle = fcTitle.substring(0,15) + "...";}
				var fcImage = clist.attr("image");
				if(fcImage=="0"){fcImage="<img src=\"/mob/temp01/img/pic.gif\" height=\"16px\" border=\"0\"/>";}
				else{fcImage="<img src=\"/mob/temp01/img/vtv.gif\" height=\"12px\" border=\"0\"/>";}
				var fcTime = clist.attr("time");
				//$("#bodybottom").html(bodyBottomToTop + ";" + waitToTop + ";" + contentId);
				var fcContenStr = contenStr.replace("{S_list_url}","/mob/productshow.asp?id=" + contentId + "&refid=" + ctRefid);
				fcContenStr = fcContenStr.replace("{S_list_title}",fcTitle);
				fcContenStr = fcContenStr.replace("{S_list_time}",fcTime);
				fcContenStr = fcContenStr.replace("{S_list_typeimg}",fcImage);
				$("#tmain").append(fcContenStr);
			});	
			$("#imgbottom").css({"display":"none"});//加载完成，隐藏加载图标
			isLoading = false;//状态调整为空闭
		},
		 error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("数据错误，请重试！");
			$("#imgbottom").css({"display":"none"});
		 } 
	});	//	
}
function checkScoll(){	
	if(isLoading){return;}//如果系统正在加载，则退出
	else{
		bodyBottomToTop = $("#bodybottom").offset().top;//底部条的位置
		waitToTop = $("#wenzhangxiabiao").offset().top;//等待条位置当bodyBottomToTop
		//$("#bodybottom").html(bodyBottomToTop + ";" + waitToTop + ";" + contentId);
		if(bodyBottomToTop>waitToTop){		
			getContent();//开始拉取内容
		}
	}
}

$(window).bind("scroll", function(){ //当滚动条滚动时
	checkScoll();
});