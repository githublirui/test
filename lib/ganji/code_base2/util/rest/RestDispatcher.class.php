<?php

/**
 * dispatch.php
 * 负责REST框架中url调度，将url定位到对应的资源类
 * 在调用前需要先定义RESOURCE_PATH，即用户自定义资源的路径
 * 使用方法：
 * 在web server设置url的rewrite规则，原url转换为/dispatch.php?url=原url。例如：
 * 转换前： http://www.ganji.com/call/CallLog?id=1
 * 转换后： http://hostname/dispatch.php?url=http://www.ganji.com/call/CallLog?id=1
 * dispatch.php会根据url参数传进来的url调用对应的资源类的相应的方法。上面的例子中，dispatch.php将会调用RESOURCE_PATH/call/CallLog.class.php中的Call_CallLog类中的get()函数
 * 输入参数保存在$_GET和$_POST中。例如，上面的例子中：$_GET['id']的值为'1'。
 *
 * 资源类中的REST方法需要操作$response对象来控制返回信息
 *
 * @author yanyan<yyquick@gmail.com>
 */
require_once dirname(__FILE__) . '/Resource.class.php';
require_once dirname(__FILE__) . '/HttpResponse.class.php';

class RestDispatcher {

	public static $response;
	public static $data;

	# 根据资源名获取对应的资源类的类名

	public static function getClassName($resourceName) {
		$ret = preg_replace('/\//', ' ', $resourceName);
		$ret = ucwords(substr($ret, 1));
		$ret = preg_replace('/ /', '_', $ret);
		return $ret;
	}

	# 将$response对象输出到客户端

	public static function response($data) {
		global $isLocal;
		if (!$isLocal) {
			self::$response->setContent($data);
			self::$response->output();
			exit;
		} else {
			self::$data = $data;
		}
	}

	public static function dispatch() {
		self::$response = new HttpResponse();

		# 获取原url，并取出资源名、输入参数和HTTP方法
		$url = $_GET['url'];

		$parts = parse_url("http://localhost" . $url);

		if (isset($parts['query'])) {
			$params = explode('&', $parts['query']);
			foreach ($params as $param) {
				$temp = explode('=', $param);
				if ($temp)
					$_GET[$temp[0]] = $temp[1];
			}
		}

		$resourceNameArray = explode('.', $parts['path']);
		if (sizeof($resourceNameArray) > 1)
			self::$response->setContentType($resourceNameArray[1]);
		else
			self::$response->setContentType('json');
		$resourceName = $resourceNameArray[0];

		$method = $_SERVER['REQUEST_METHOD'];

		if (strpos($resourceName, "/list")) {
			$method = "LIST";
			$resourceName = substr($resourceName, 0, -5);
		}

		if (isset($_REQUEST['_method'])) {
			$method = $_REQUEST['_method'];
		}

		if (!is_file(RESOURCE_PATH . $resourceName . '.class.php'))
			exit;

		require_once RESOURCE_PATH . $resourceName . '.class.php';

		# 生成资源对象，并且调用对应的方法
		$classBame = self::getClassName($resourceName);

		$obj = new $classBame;
		switch ($method) {
			case 'GET':
				$obj->get();
				break;
			case 'POST':
				$obj->post();
				break;
			case 'PUT':
				$obj->put();
				break;
			case 'DELETE':
				$obj->delete();
				break;
			case 'LIST':
				$obj->items();
				break;
		}
	}

}
