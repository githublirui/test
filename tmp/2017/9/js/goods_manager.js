var goodesManager = (function () {

	return {
		init: function () {
			this.eventHandler();
		},
		eventHandler: function () {
			//导航事件
			$('li', '#nav').on('click', function () {
				var session = $(this).data('session');
				$(this).addClass('active').siblings().removeClass('active');
				$('.session').hide();
				$('#' + session).show();
			});

			//正在销售列表绑定事件
			$('#selling').on('click', '.goodsList li', function () {
				var that = this;
				goodesManager.bottomPop('selling');
				$('ul', '#bottomPop').animate({bottom:0},200);
				//取消事件
				$('.cancel', '#bottomPop').on('click', function () {
					$(this).off('click');
					$('#bottomPop').remove();
				});
				//删除事件
				$('.delete', '#bottomPop').on('click', function () {
					$(this).off('click');
					$('#bottomPop').remove();
					$(that).remove();
					goodesManager.alertPop('删除成功');
				});

				//修改商品价格库存
				$('.revise', '#bottomPop').on('click', function () {
					$('#bottomPop').remove();
					var data = {
						name: $(that).find('.goods_name').text(),
						price: $(that).find('.price').text(),
						number: $(that).find('.goodsNumber').text()
					}
					goodesManager.revisePop(data, that);
				});
			});

			//橱窗推荐列表绑定事件
			$('#sellHot').on('click', '.goodsList li', function () {
				var that = this;
				goodesManager.bottomPop('sellHot');
				$('ul', '#bottomPop').animate({bottom:0},200);
				$('.cancel', '#bottomPop').on('click', function () {
					$(this).off('click');
					$('#bottomPop').remove();
				});

				//取消推荐商品事件
				$('.delete', '#bottomPop').on('click', function () {
					$(this).off('click');
					$('#bottomPop').remove();
					$(that).remove();
					goodesManager.alertPop('取消橱窗推荐成功');
				});

				//修改商品价格库存
				$('.revise', '#bottomPop').on('click', function () {
					$('#bottomPop').remove();
					var data = {
						name: $(that).find('.goods_name').text(),
						price: $(that).find('.price').text(),
						number: $(that).find('.goodsNumber').text()
					}
					goodesManager.revisePop(data, that);
				});
			});

		},
		bottomPop: function (sessionID, data) {
			if (sessionID === 'selling') {
				var bottomPopHtml = '<div id="bottomPop">'
					+'	<ul>'
					+'		<li>'
					+'			<a href="javascript:;">查看商品二维码</a>'
					+'		</li>'
					+'		<li>'
					+'			<a href="javascript:;" class="revise">修改商品库存价格</a>'
					+'		</li>'
					+'		<li>'
					+'			<a href="javascript:;" class="delete">删除商品</a>'
					+'		</li>'
					+'		<li style="border:0;margin-bottom:0.12rem;">'
					+'			<a href="javascript:;">设置成为推荐商品</a>'
					+'		</li>'
					+'		<li>'
					+'			<a href="javascript:;" class="cancel">取消</a>'
					+'		</li>'
					+'	</ul>'
					+'</div>';
			} else if (sessionID === 'sellHot') {
				var bottomPopHtml = '<div id="bottomPop">'
					+'	<ul>'
					+'		<li>'
					+'			<a href="javascript:;">查看商品二维码</a>'
					+'		</li>'
					+'		<li>'
					+'			<a href="javascript:;" class="revise">修改商品库存价格</a>'
					+'		</li>'
					+'		<li style="border:0;margin-bottom:0.12rem;">'
					+'			<a href="javascript:;" class="delete">取消推荐商品</a>'
					+'		</li>'
					+'		<li>'
					+'			<a href="javascript:;" class="cancel">取消</a>'
					+'		</li>'
					+'	</ul>'
					+'</div>';
			}
			$('body').append(bottomPopHtml);
		},
		/**
		 *提示弹窗
	     *@params {strimg} message 要提示的信息
	     *@return null
	     */
		alertPop: function (message) {
			var alertPopHtml = '<div id="alertPop">'
				+'	<span class="alert-ico"></span>'
				+'	<p>'+ message +'</p>'
				+'</div>';
			$('body').append(alertPopHtml);	
			setTimeout(function () {
				$('#alertPop').remove();
			},1000);
		},
		/**
		 *修改库存价格弹窗
	     *@params {object} data {name:'商品名称', price:100, number:10}
	     *@params {object} item jquery对象，选中的商品列表元素
	     *@return null
	     */
		revisePop: function (data, item) {
			var revisePopHtml = '<div id="revisePop">'
				+'	<div class="revisePop-inner">'
				+'		<h1>修改商品库存</h1>'
				+'		<ul>'
				+'			<li>'
				+'				<div class="lable-cell">名称</div>'
				+'				<div class="input-cell"><input type="text" class="dataName" value="'+ data.name +'" /></div>'
				+'			</li>'
				+'			<li>'
				+'				<div class="lable-cell">价格</div>'
				+'				<div class="input-cell"><input type="number" class="dataPrice" value="'+ data.price +'" /></div>'
				+'			</li>'
				+'			<li>'
				+'				<div class="lable-cell">库存</div>'
				+'				<div class="input-cell"><input type="number" class="dataNumber" value="'+ data.number +'" /></div>'
				+'			</li>'
				+'		</ul>'
				+'		<p>'
				+'			<a href="javascript:;" class="cancel">取消</a>'
				+'			<a href="javascript:;" class="enter">确认</a>'
				+'		</p>'
				+'	</div>'
				+'</div>';
			$('body').append(revisePopHtml);	
			$('.cancel', '#revisePop').on('click', function () {
				$('#revisePop').remove();
			});
			$('.enter', '#revisePop').on('click', function () {
				var dataName = $('.dataName', '#revisePop').val();
				var dataPrice = $('.dataPrice', '#revisePop').val();
				var dataNumber = $('.dataNumber', '#revisePop').val();
				console.log(dataPrice)
				$(item).find('.goods_name').text(dataName);
				$(item).find('.price').text(dataPrice);
				$(item).find('.goodsNumber').text(dataNumber);
				$('#revisePop').remove();
			});
		}
	}
})();
$(function () {
	goodesManager.init();
});