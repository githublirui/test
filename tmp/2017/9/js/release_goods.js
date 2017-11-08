var releaseGoods = (function () {

	//设置价格和邮费的条数
	var setPriceNum = 2,
		setPostageNum = 0;
	return {
		init: function () {
			this.eventHandler();
		},
		eventHandler: function () {
			//更多展开收起
			$(".more-open").click(function () {
				var text = $(this).text();
				if (text === "收起") {
					$(this).text("更多选项+");
					$(".release-goods-more").hide();
				} else {
					$(this).text("收起");
					$(".release-goods-more").show();
				}
			});
			//图片上传
			$("#upLoadList").on("change", '.file', function() {
				var $parent = $(this).parent();
				if (!this.files.length) return;
				var files = Array.prototype.slice.call(this.files);
				console.log(files)
				files.forEach(function(file, i) {
				  if (!/\/(?:jpeg|png|gif)/i.test(file.type)) return;
				  var reader = new FileReader();
				  reader.onload = function() {
					var result = this.result;
					$("<li><img src='"+result+"' /></li>").insertBefore($parent);
				  };
				  reader.readAsDataURL(file);
				})
				$(this).hide();
				$('<input type="file" accept="image/*" class="file" name="file">').insertAfter($(this));

			});
			//支付协议弹窗
			$('.agreement').click(function () {
				$('#payProtocol').show();
			});
			$('.closeBtn','#payProtocol').click(function () {
				$('#payProtocol').hide();
			});

			//设置地区弹窗
			$('#setpostageList').on('click', '.setArea', function () {
				var $that = $(this);
				$('#setAreaPop').show();
				$('li','#setAreaPop').off('click').on('click', function () {
					$(this).find(':radio').attr('checked', true);
					var inputVal = $(this).find('span').text();
					$that.val(inputVal);
					$('#setAreaPop').hide();
				});
			});

			//设置价格
			$('#J_setPriceBtn').click(function () {
				$('#release-goods-container').hide();
				$('#setPriceContainer').show();
			});
			$('.add-btn', '#setPriceContainer').click(function () {
				setPriceNum++;
				var priceHtml = '<div class="setPrice-item">'
					+'	<h4>第'+ setPriceNum +'套价格与规则</h4>'
					+'	<ul class="message-list">'
					+'		<li>'
					+'			<div class="label-cell">说明</div>'
					+'			<input type="text" class="input-cell" placeholder="例如iphone6港行，国行">'
					+'			<a href="#" class="set_btn">设置多种价格</a>'
					+'		</li>'
					+'		<li>'
					+'			<div class="label-cell">价格</div>'
					+'			<input type="number" class="input-cell" placeholder="单位（元）" onkeyup="this.value=this.value.replace(/\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\D/g,\'\')">'
					+'			<a href="#" class="set_btn">设置多种价格</a>'
					+'		</li>'
					+'		<li>'
					+'			<div class="label-cell">库存数</div>'
					+'			<input type="number" class="input-cell" value="10" onkeyup="this.value=this.value.replace(/\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\D/g,\'\')">'
					+'		</li>'
					+'	</ul>'
					+'</div>';
				$('#setPriceList').append(priceHtml);
			});
			$('.cancel', '#setPriceContainer').click(function () {
				$('#setPriceContainer').hide();
				$('#release-goods-container').show();
			});
			$('.submit', '#setPriceContainer').click(function () {
				alert('提交')
			});

			//设置邮费价格
			$('#J_setPostageBtn').click(function () {
				$('#release-goods-container').hide();
				$('#setpostageContainer').show();
				releaseGoods.addPostageHtml();
			});
			$('.add-btn', '#setpostageContainer').click(function () {
				releaseGoods.addPostageHtml();
			});
			$('.cancel', '#setpostageContainer').click(function () {
				$('#setpostageContainer').hide();
				$('#release-goods-container').show();
			});
			$('.submit', '#setpostageContainer').click(function () {
				alert('提交')
			});

			//点击以前使用过邮费模板
			$('#goHistoryPostage').click(function () {
				$('#setpostageContainer').hide();
				$('#newlyPostage').show();
			});
			$('li','#newlyPostage').click(function () {
				$('#newlyPostage').hide();
				$('#setpostageContainer').show();	
				$('#setpostageList').empty();
				setPostageNum = 0;	
			});
		},
		//添加邮费模块
		addPostageHtml: function () {
			setPostageNum++;
			var postageHtml = '<div class="setPrice-item">'
				+'	<h4>第'+ setPostageNum +'套地区与邮费</h4>'
				+'	<ul class="message-list">'
				+'		<li>'
				+'			<div class="label-cell">地区</div>'
				+'			<input type="text" class="input-cell setArea" readonly="true" />'
				+'			<span class="dir-right"></span>'
				+'		</li>'
				+'		<li>'
				+'			<div class="label-cell">邮费</div>'
				+'			<input type="number" class="input-cell" placeholder="单位（元）" onkeyup="this.value=this.value.replace(/\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\D/g,\'\')">'
				+'		</li>'
				+'	</ul>'
				+'</div>';
			$('#setpostageList').append(postageHtml);
		}
	}
})();
$(function () {
	releaseGoods.init();
})