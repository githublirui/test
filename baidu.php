<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
<script type="text/javascript" > 
var origin = "0,0"; 
var origin_region = ""; 
//var point = new BMap.Point(118.185789, 24.489321); 
//var map = new BMap.Map("mapContainer"); 
//map.enableScrollWheelZoom(); 
//map.centerAndZoom(point, 15); 
//var marker = new BMap.Marker(point); 
//map.addOverlay(marker);

//var gectrl=new BMap.GeolocationControl( { 
//anchor:BMAP_ANCHOR_TOP_LEFT, 
//enableAutoLocation: true }); 
//map.addControl(gectrl); //添加定位控件 


var myCity = new BMap.LocalCity(); 
myCity.get(function(result){ origin_region = result.name; });
var geolocation = new BMap.Geolocation(); 
geolocation.getCurrentPosition(function (r) { 
if (this.getStatus() == BMAP_STATUS_SUCCESS ) { 
origin = r.point.lng + "," + r.point.lat; 
	alert(origin);
} 
})
</script>


http://map.baidu.com/mobile/#place/list/qt=s&wd=%E7%BE%8E%E9%A3%9F&searchFlag=sort&c=131&center_rank=1&center_name=%E6%88%91%E7%9A%84%E4%BD%8D%E7%BD%AE&show_select=0