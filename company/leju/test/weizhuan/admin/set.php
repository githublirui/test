<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');
//print_r($config);
//设置
if($_POST['do']=='set'){
	$pp=guolv($_POST['pp']);
	$ti=guolv($_POST['ti']);
	$p1=guolv($_POST['p1']);
	$site=guolv($_POST['site']);
	$sharesite=guolv($_POST['sharesite']);
	$song=guolv($_POST['song']);
	$openreg=guolv($_POST['openreg']);
	$sitename=$_POST['sitename'];
	$kou_pr=guolv($_POST['kou_pr']);
	$kou_day=guolv($_POST['kou_day']);
	$kou_hour=guolv($_POST['kou_hour']);
	$yunpian=guolv($_POST['yunpian']);
	$safe=guolv($_POST['safe']);
	$qqkf=guolv($_POST['qqkf']);
	$tc=guolv($_POST['tc']);
	$iphour=guolv($_POST['iphour']);
	$fangwen=guolv($_POST['fangwen']);
	$area=guolv($_POST['area']);
	$city=guolv($_POST['city']);
	$weixin_reg=guolv($_POST['weixin_reg']);
	$daysong=guolv($_POST['daysong']);
	$ipreg2=guolv($_POST['ipreg2']);
	$reg_yzr1=guolv($_POST['reg_yzr1']);
	$gotimes=guolv($_POST['gotimes']);
	$AdminLoginCode=guolv($_POST['AdminLoginCode']);
	$AdminEmail=guolv($_POST['AdminEmail']);
	$UserAddArticle=guolv($_POST['UserAddArticle']);
	$UserAddArticleType=guolv($_POST['UserAddArticleType']);
	$DetailMoreNum=guolv($_POST['DetailMoreNum']);
	$DetailPvOpen=guolv($_POST['DetailPvOpen']);
	$arr=array(
			'site'=>$site,
			'sharesite'=>$sharesite,
			'ti'=>$ti,
			'p1'=>$p1,
			'pp'=>$pp,
			'song'=>$song,
			'openreg'=>$openreg,
			'sitename'=>$sitename,
			'kou_pr'=>$kou_pr,
			'kou_day'=>$kou_day,
			'kou_hour'=>$kou_hour,	
			'yunpian'=>$yunpian,
			'safe'=>$safe,
			'qqkf'=>$qqkf,
			'tc'=>$tc,
			'iphour'=>$iphour,
			'fangwen'=>$fangwen,
			'area'=>$area,//区域
			'city'=>$city,//城市
			'weixin_reg'=>$weixin_reg,//城市
			'daysong'=>$daysong,//城市
			'ipreg2'=>$ipreg2,//相同ip重复注册			
			'reg_yzr1'=>$reg_yzr1,//相同ip重复注册
			'gotimes'=>$gotimes,//延迟计费
			'AdminLoginCode'=>$AdminLoginCode,//后台登录验证码
			'AdminEmail'=>$AdminEmail,//管理员邮箱
			'UserAddArticle'=>$UserAddArticle,//前台会员发布文章
			'UserAddArticleType'=>$UserAddArticleType,//前台会员发布文章分类
			'DetailMoreNum'=>$DetailMoreNum,//文章页相关文章推荐数量
			'DetailPvOpen'=>$DetailPvOpen,//文章页阅读数展示
			
	);
	$config=var_export($arr, true);
	file_put_contents('../config.php', "<?php\nreturn $config;\n?>");
	//print_r($arr);
	echo "<script>alert('修改成功');location.href='set.php'</script>";
	exit;
}
//扣量
if($_POST['do']=='kou'){

}
?>

<?php include('head.php')?>    
    <div class="container-fluid">
        
        <div class="row-fluid">
		<?php
		include('left.php');
		?>
<div class="span9">
            <h1 class="page-title">系统设置</h1>
	<form action="set.php" method="post">		
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">基本设置</a></li>
	  <li><a href="#fangwen" data-toggle="tab">访问设置</a></li>
	  <li><a href="#neirong" data-toggle="tab">内容设置</a></li>
	  <li><a href="#tuiguang" data-toggle="tab">推广运营</a></li>
	  <li><a href="#api" data-toggle="tab">第三方API</a></li>
	  <li><a href="#kouliang" data-toggle="tab">扣量设置</a></li>
	  <li><a href="#system" data-toggle="tab">系统环境</a></li>
	  <li class="pull-right"><button class="btn btn-info">保存提交</button></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
	  <input type="hidden" name="do" value="set"> 
	    <label>网站标题：</label>
        <input type="text" value="<?php echo $config['sitename']?>" style="width:300px" name="sitename">    
		<label>主站网址：</label>
        <input type="text" value="<?php echo $config['site']?>" style="width:300px" name="site"> <code>如：http://www.baidu.com</code>
		<label>分享网址：</label>
        <input type="text" value="<?php echo $config['sharesite']?>" style="width:300px" name="sharesite"> <code>如：www.baidu.com（多域名用#分开，开头不要带http://）</code>  
		<label>QQ客服：</label>
        <input type="text" value="<?php echo $config['qqkf']?>" style="width:300px" name="qqkf">
		<label>管理员邮箱：</label>
        <input type="text" value="<?php echo $config['AdminEmail']?>" style="width:300px" name="AdminEmail">	
		<label>最低提现：</label>
        <input type="text" value="<?php echo $config['ti']?>" style="width:300px" name="ti"> 元
		<label>后台验证码：</label>
		<input type="text" value="<?php echo $config['AdminLoginCode']?>" style="width:300px" name="AdminLoginCode"> <code>0开启 1关闭 （使用火车头发布，请关闭）</code>
      </div>
 
	  
	  <div class="tab-pane fade" id="kouliang">	
	  <!--扣量-->
	  	<label>防作弊：</label>
        <input type="text" value="<?php echo $config['safe']?>" style="width:300px" name="safe">	 <code>0普通防御 1高级防御</code>
		<label>扣量比例：</label>
        <input type="text" value="<?php echo $config['kou_pr']?>" style="width:300px" name="kou_pr"> %	<code>设置范围10%-80%</code>
	    <label>注册后几天开始扣量：</label>
        <input type="text" value="<?php echo $config['kou_day']?>" style="width:300px" name="kou_day"><code>如果想关闭扣量可以设置99999</code>
	    <label>扣量时间：</label>
        <input type="text" value="<?php echo $config['kou_hour']?>" style="width:300px" name="kou_hour"><code>例如：1,2,3,7,20 表示1点，2点，3点，7点，和晚上8点扣量	</code>
	  </div>
	  
	  <div class="tab-pane fade" id="system">	
	  <!--系统环境-->
	  	<label>操作系统：<?php echo PHP_OS?></label>
		<label>运行环境：<?php echo $_SERVER["SERVER_SOFTWARE"]?></label>
		<label>上传附件限制：<?php echo ini_get('upload_max_filesize')?></label>
		<label>执行时间限制：<?php echo ini_get('max_execution_time').'秒'?></label>
		<label>服务器时间：<?php echo date("Y年n月j日 H:i:s")?></label>
		<label>北京时间：<?php echo gmdate("Y年n月j日 H:i:s",time() + 8 * 3600)?></label>
		<label>服务器域名：<?php echo $_SERVER['SERVER_NAME']?></label>
		<label>剩余空间：<?php echo round((@disk_free_space(".") / (1024 * 1024)),2).'M'?></label>
		<label>register_globals：<?php echo get_cfg_var("register_globals")=="1" ? "ON" : "OFF"?></label>
		<label>magic_quotes_gpc：<?php echo (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO'?></label>
		<label>magic_quotes_runtime：<?php echo (1 === get_magic_quotes_runtime())?'YES':'NO'?></label>
		</div>
	  
	  <div class="tab-pane fade" id="neirong">	
	  <!--内容管理-->
	  	<label>文章页显示阅读数：</label>
        <input type="text" value="<?php echo $config['DetailPvOpen']?>" style="width:300px" name="DetailPvOpen">	 <code>1开启 0关闭 其他数字表示虚拟阅读量</code>
		<label>文章页相关内容推荐数量：</label>
		<input type="text" value="<?php echo $config['DetailMoreNum']?>" style="width:300px" name="DetailMoreNum">	 <code>0关闭推荐 其他数字表示推荐的数量，建议不要超过20</code>
		<hr>
		<label>前台会员支持文章发布</label>
		<input type="text" value="<?php echo $config['UserAddArticle']?>" style="width:300px" name="UserAddArticle">	 <code>0不支持 1支持微信导入</code>
		<label>前台会员支持发布文章的分类</label>
		<input type="text" value="<?php echo $config['UserAddArticleType']?>" style="width:300px" name="UserAddArticleType">	 <code>31,搞笑,0.01  表示分类ID是31，分类名称是搞笑，点击单价是0.01</code>				
	 </div>
	  
	  <div class="tab-pane fade" id="api">	
	  <!--第三方API-->
	    <label>短信接口KEY：</label>
        <input type="text" value="<?php echo $config['yunpian']?>" style="width:300px" name="yunpian">	 <code><a href="http://www.yunpian.com" target="_blank">注册地址>></a> 不填写关闭注册验证码	</code>	  
	  </div>
	  
	  <div class="tab-pane fade" id="fangwen">	
	  <!--访问-->
		<label>开放注册：</label>
		<input type="text" value="<?php echo $config['openreg']?>" style="width:300px" name="openreg"> <code>0关闭注册，1手机注册</code>
		<label>相同IP禁止重复注册</label>
		<input type="text" value="<?php echo $config['ipreg2']?>" style="width:300px" name="ipreg2"><code>0允许重复注册，1禁止重复注册</code>
		<label>微信免注册：</label>
		<input type="text" value="<?php echo $config['weixin_reg']?>" style="width:300px" name="weixin_reg">	<code>0关闭，1开启</code>
		<label>是否支持访问</label>
		<input type="text" value="<?php echo $config['fangwen']?>" style="width:300px" name="fangwen"><code>1仅限手机访问，2仅限微信访问，3支持任何访问，4禁止全站访问，5 仅支持QQ来访，6，仅支持微博来访</code>
		<label>推广地区：</label>
		<select name="area" onchange="chinaChange(this,document.getElementById('city'));" style=" width:10%; height:30px;line-height:30px; ">
		<option value ="全国">全国</option>
		<option value ="北京市">
		北京市 </option><option value ="天津市">
		天津市 </option><option value ="上海市">
		上海市 </option><option value ="重庆市">
		重庆市 </option><option value ="河北省">
		河北省 </option><option value ="山西省">
		山西省 </option><option value ="辽宁省">
		辽宁省 </option><option value ="吉林省">
		吉林省 </option><option value ="黑龙江省">
		黑龙江省</option><option value ="江苏省"> 
		江苏省 </option><option value ="浙江省">
		浙江省 </option><option value ="安徽省">
		安徽省 </option><option value ="福建省">
		福建省 </option><option value ="江西省">
		江西省 </option><option value ="山东省">
		山东省 </option><option value ="河南省">
		河南省 </option><option value ="湖北省">
		湖北省 </option><option value ="湖南省">
		湖南省 </option><option value ="广东省">
		广东省 </option><option value ="海南省">
		海南省 </option><option value ="四川省">
		四川省 </option><option value ="贵州省">
		贵州省 </option><option value ="云南省">
		云南省 </option><option value ="陕西省">
		陕西省 </option><option value ="甘肃省">
		甘肃省 </option><option value ="青海省">
		青海省 </option><option value ="台湾省">
		台湾省 </option><option value ="广西壮族自治区">
		广西壮族自治区</option><option value ="内蒙古自治区"> 
		内蒙古自治区</option><option value ="西藏自治区"> 
		西藏自治区</option><option value ="宁夏回族自治区"> 
		宁夏回族自治区 </option><option value ="新疆维吾尔自治区">
		新疆维吾尔自治区</option><option value ="香港特别行政区">
		香港特别行政区</option><option value ="澳门特别行政区">
		澳门特别行政区</option>
		</select>
		<select name="city" id="city" style=" width:10%; height:30px;line-height:30px; ">
		<option value ="地区">地区</option>
		</select><br />
		目前区域：<font color=green><?php echo $config['area']?><?php echo $config['city']?></font>	  
	  </div>
	  
	  <div class="tab-pane fade" id="tuiguang">	
	  <!--推广-->
	    <label>注册账户赠送：</label>
        <input type="text" value="<?php echo $config['song']?>" style="width:300px" name="song"> 元		
	    <label>注册徒弟师傅奖励：</label>
        <input type="text" value="<?php echo $config['reg_yzr1']?>" style="width:300px" name="reg_yzr1"> 元		   
	    <label>每日登录赠送：</label>
        <input type="text" value="<?php echo $config['daysong']?>" style="width:300px" name="daysong"> 元				
	    <label>下线提成：</label>
        <input type="text" value="<?php echo $config['tc']?>" style="width:300px" name="tc">%，<code>提现时统一发放到账户</code>
	  </div>	  
  </div>

</div>

        </div>
    </div>
    

    </form>
    <footer>
        <hr>
        
        <p class="pull-right"><!--power by right--></p>
        
        
        <p><!--power by --></p>
    </footer>
    

    

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">

var china=new Object();
// china['北京市']=new Array('','');
// china['上海市']=new Array('','');
// china['天津市']=new Array('','');
// china['重庆市']=new Array('','');
china['河北省'] = new Array('石家庄', '唐山市', '邯郸市', '秦皇市岛', '保市定', '张家市口', '承德市', '廊坊市', '沧州市', '衡水市', '邢台市');
china['山西省']=new Array('太原市','大同市','阳泉市','长治市','晋城市','朔州市','晋中市','运城市','忻州市','临汾市','吕梁市');
china['辽宁省']=new Array('沈阳市','大连市','鞍山市','抚顺市','本溪市','丹东市','锦州市','营口市','阜新市','辽阳市','盘锦市','铁岭市','朝阳市','葫芦岛市');
china['吉林省']=new Array('长春市','吉林市','四平市','辽源市','通化市','白山市','松原市','白城市','延边州','长白山管委会');
china['黑龙江省']=new Array('哈尔滨市','齐齐哈尔市','大庆市','佳木斯市','牡丹江市','七台河市','双鸭山市','黑河市','鸡西市','伊春市','绥化市','鹤岗市','加格达奇市');
china['江苏省']=new Array('南京市','苏州市','无锡市','常州市','南通市','扬州市','镇江市','泰州市','盐城市','连云港市','宿迁市','淮安市','徐州市');
china['浙江省']=new Array('杭州市','宁波市','温州市','嘉兴市','湖州市','绍兴市','金华市','衢州市','舟山市','台州市','丽水市');
china['安徽省']=new Array('合肥市','芜湖市','蚌埠市','淮南市','马鞍山市','淮北市','铜陵市','安庆市','黄山市','滁州市','阜阳市','宿州市','巢湖市','六安市','亳州市','池州市','宣城市');
china['福建省']=new Array('福州市','厦门市','莆田市','三明市','泉州市','漳州市','南平市','龙岩市','宁德市');
china['江西省']=new Array('南昌市','景德镇市','萍乡市','九江市','新余市','鹰潭市','赣州市','吉安市','宜春市','抚州市','上饶市');
china['山东省']=new Array('济南市','青岛市','淄博市','枣庄市','东营市','烟台市','潍坊市','济宁市','泰安市','威海市','日照市','莱芜市','临沂市','德州市','聊城市','滨州市','菏泽市');
china['河南省']=new Array('郑州市','开封市','洛阳市','平顶山市','安阳市','鹤壁市','新乡市','焦作市','濮阳市','许昌市','漯河市','三门峡市','南阳市','商丘市','信阳市','周口市','驻马店市');
china['湖北省']=new Array('武汉市','黄石市','十堰市','荆州市','宜昌市','襄樊市','鄂州市','荆门市','孝感市','黄冈市','咸宁市','随州市');
china['湖南省']=new Array('长沙市','株洲市','湘潭市','衡阳市','邵阳市','岳阳市','常德市','张家界市','益阳市','郴州市','永州市','怀化市','娄底市');
china['广东省']=new Array('广州市','深圳市','珠海市','汕头市','韶关市','佛山市','江门市','湛江市','茂名市','肇庆市','惠州市','梅州市','汕尾市','河源市','阳江市','清远市','东莞市','中山市','潮州市','揭阳市','云浮市');
china['海南省']=new Array('文昌市','琼海市','万宁市','五指山市','东方市','儋州市');
china['四川省 ']=new Array('成都市','自贡市','攀枝花市','泸州市','德阳市','绵阳市','广元市','遂宁市','内江市','乐山市','南充市','眉山市','宜宾市','广安市','达州市','雅安市','巴中市','资阳市');
china['贵州省']=new Array('贵阳市','六盘水市','遵义市','安顺市');
china['云南省']=new Array('昆明市','曲靖市','玉溪市','保山市','昭通市','丽江市','普洱市','临沧市');
china['陕西省']=new Array('西安市','铜川市','宝鸡市','咸阳市','渭南市','延安市','汉中市','榆林市','安康市','商洛市');
china['甘肃省']=new Array('兰州市','金昌市','白银市','天水市','嘉峪关市','武威市','张掖市','平凉市','酒泉市','庆阳市','定西市','陇南市');
china['青海省']=new Array('西宁市');
china['台湾省'] = new Array('台北市','高雄市','基隆市','台中市','台南市','新竹市','嘉义市');
china['广西壮族自治区']=new Array('南宁市','柳州市','桂林市','梧州市','北海市','防城港市','钦州市','贵港市','玉林市','百色市','贺州市','河池市','来宾市','崇左市');
china['内蒙古自治区']=new Array('呼和浩特市','包头市','乌海市','赤峰市','通辽市','鄂尔多斯市','呼伦贝尔市','巴彦淖尔市','乌兰察布市'); 
china['西藏自治区']=new Array('拉萨市');
china['宁夏回族自治区']=new Array('银川市','石嘴山市','吴忠市','固原市','中卫市');
china['新疆维吾尔自治区']=new Array('乌鲁木齐市','克拉玛依市');
china['香港特别行政区']=new Array('台北市','高雄市','基隆市','台中市','台南市','新竹市','嘉义市');
function chinaChange(province, city) {
var pv, cv;
var i, ii;
pv = province.value;
cv = city.value;
city.length = 1;
if (pv == '0') return;
if (typeof (china[pv]) == 'undefined') return;


for (i = 0; i < china[pv].length; i++) { 
ii = i + 1;

city.options[ii] = new Option();
city.options[ii].text = china[pv][i];
city.options[ii].value = china[pv][i];
}
city.options[0].text = "请选择市区";

};
</script>


  </body>
</html>


