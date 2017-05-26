var twidth;
function tts(zdt){
	twidth = document.body.clientWidth-26;
	if(twidth>500){twidth=500;}
	var ig = new Image();
	ig.src = zdt.src;	
	if(zdt.width==twidth){
		zdt.width = ig.width;
		zdt.height = ig.height;
		zdt.src = ig.src;
	}else{
		if(zdt.width>twidth){
			zdt.width = twidth;
			zdt.height = twidth/ig.width * ig.height;
		}
	}
}

$(function(){
	$("iframe.video_iframe").each(function(i,zdt){
		$(this).attr("srcloaded","");
	});
	$("iframe.video_iframe").each(function(i,zdt){
		if (! $(zdt).attr("srcloaded") ){
			$(zdt).attr("srcloaded","1");
			ttsvfunc(zdt);
		}
	});
});
function ttsv(zdt){
	if ( $(zdt).attr("srcloaded") ){
		return false;
	}
	$(zdt).attr("srcloaded","1");
	ttsvfunc(zdt);
}
function ttsvfunc(zdt){
	var hsrc = $(zdt).attr("src");
	if (! hsrc ){ hsrc = $(zdt).attr("data-src"); }
	if (hsrc){
		twidth = document.body.clientWidth-26;
		if(twidth>500){twidth=500;}
		var fwidth = $(zdt).width();
		var fheight = $(zdt).height();
		if(twidth!=fwidth){
			var theight = parseInt(twidth*2/3);
			$(zdt).attr('height',theight);
			$(zdt).attr('width',twidth);
			$(zdt).height(theight);
			$(zdt).width(twidth);
			$(zdt).css("height",theight+'px');
			$(zdt).css("width",twidth+'px');
			//alert($(zdt).height())
			hsrc = hsrc.replace(/width=[\d.]+/ig,"width=" + twidth).replace(/height=[\d.]+/ig,"height=" + theight);
			$(zdt).attr("src",hsrc);
		}else{
			var theight = fheight;
			hsrc = hsrc.replace(/width=[\d.]+/ig,"width=" + twidth).replace(/height=[\d.]+/ig,"height=" + theight);
			$(zdt).attr("src",hsrc);
		}
		
		var hstyle = $(zdt).attr("style");
		hstyle = hstyle.replace(/width[:][ ]*[\d.]+px/ig,"width:" + twidth+'px').replace(/height[:][ ]*[\d.]+px/ig,"height:" + theight+'');
		$(zdt).attr('style',hstyle)
	}
	

}