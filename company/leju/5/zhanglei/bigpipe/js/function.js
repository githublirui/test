function viewer(data){
	var html	= data.html;
	var domid	= data.domid;
	$('#'+domid).html(html);
}