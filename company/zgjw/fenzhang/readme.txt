一、classes下文件：实现基础接口逻辑，一般无需修改
1、RequestHandler.class.php所有请求类的基类
2、PayRequestHandler.class.php支付请求类
3、BaseSplitRequestHandler.class.php分账、支付并分账、冻结、解冻、分账回退、平台退款、订单查询的请求类
4、ResponseHandler.class.php页面交互模式的应答基类
5、client/ClientResponseHandler.class.php后台系统调用模式的应答基类，支持XML格式
6、client/ScriptClientResponseHandler.class.php后台系统调用模式处理返回类HTML格式的应答类
7、TrustRefundResponseHandler.class.php委托退款关系建立、撤销的应答类
8、client/TenpayHttpClient.class.php通讯类，支持http、https、双向https

二、根目录下的php的文件：调用的例子，需要根据业务情况调整，client开头的为后台系统调用模式接口
1、payRequest.php支付请求例子
2、payResponse.php支付应答例子
3、show.php结果展现页
4、splitPayRequest.php支付并分账请求例子
5、splitPayRequest.php支付并分账应答例子
6、trustRefundRequest.php建立委托退款关系请求例子
7、trustRefundRequest.php委托退款关系建立、撤销应答例子
8、clientSplit.php分账调用例子
9、clientSplitRollback.php分账退款调用例子
10、clientPlatformRefund.php平台退款调用例子
11、clientSplitInquire.php订单查询调用例子
12、clientUserCheck.php用户信息验证调用例子
13、clientFreeze.php冻结调用例子
14、clientThaw.php解冻调用例子

