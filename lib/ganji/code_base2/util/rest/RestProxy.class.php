<?php

require_once dirname(__FILE__) . '/Util.class.php';

class RestProxy {
	/**
	 * 五种请求方式
	 *
	 * @var string
	 */
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_LIST = 'LIST';
	const METHOD_PUT = 'PUT';
	const METHOD_DELETE = 'DELETE';

	private $running = 0;
	private $mch;
	private $ch;
	private $meta;
	private static $phpRedis = NULL;

	public function __construct() {
	}

	public static function getRedis() {
		if (empty(self::$phpRedis)) {
			self::$phpRedis = new Redis();
			self::$phpRedis->connect(RestProxyConfig::REDIS_SERVER);
		}
		return self::$phpRedis;
	}

	/**
	 * 调用REST服务方法
	 *
	 * @param string $method 调用方法，'GET' 'POST' 'DELETE' 'PUT' 'LIST'等
	 * @param string $host 主机
	 * @param string $resource 资源名
	 * @param array $params 输入参数
	 * @param string $contentType 返回内容类型
	 * @param int $connectTimeout 连接超时（单位：秒）
	 * @param int $readTimeout 调用超时（单位：秒）
	 * @return depend on the content type if success, FALSE if failed
	 */
	public static function callMethod($method, $host, $resource, $params, $contentType = "json", $connectTimeout = 5, $readTimeout = 10) {
		// create curl resource
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $readTimeout);

		// 这个选项是为了防止lighttpd返回417错误而加的。参考链接：http://hi.baidu.com/loveyoursmile/blog/item/297fc8bf8e7d560718d81f56.html
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

		// set method
		$resource .= ".{$contentType}";
		$params['_method'] = $method;
		$str_params = Util::encodeArray($params);
		if ($method == self::METHOD_POST || $method == self::METHOD_PUT || $method == self::METHOD_DELETE) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::METHOD_POST);
			curl_setopt($ch, CURLOPT_URL, "http://{$host}/{$resource}");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str_params);
		}

		if ($method == self::METHOD_GET || $method == self::METHOD_LIST) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::METHOD_GET);
			curl_setopt($ch, CURLOPT_URL, "http://{$host}/{$resource}?{$str_params}");
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string
		$output = curl_exec($ch);

		// 增加报警
		if ($output === FALSE) {
			require_once dirname(__FILE__) . '/../log/Logger.class.php';
			Logger::logError("REST服务请求调用失败,地址:http://{$host}/{$resource},方式:{$method},参数:{$str_params},数据格式:{$contentType},出错文件:".__FILE__.",行数:".__LINE__, "rest.proxy");
		} else if ($contentType == 'json')
			$output = json_decode($output, true);

		// close curl resource to free up system resources
		curl_close($ch);

		return $output;
	}

	/**
	 * 异步调用REST服务方法
	 *
	 * @param string $method 调用方法，'GET' 'POST' 'DELETE' 'PUT' 'LIST'等
	 * @param string $host 主机
	 * @param string $resource 资源名
	 * @param array $params 输入参数
	 * @param string $contentType 返回内容类型
	 * @param int $connectTimeout 连接超时（单位：秒）
	 * @param int $readTimeout 调用超时（单位：秒）
	 * @return 无返回
	 */
	public function callMethodAsync($method, $host, $resource, $params, $contentType = "json", $connectTimeout = 5, $readTimeout = 10) {

		$this->meta = array();
		$this->meta['method'] = $method;
		$this->meta['host'] = $host;
		$this->meta['resource'] = $resource;
		ksort($params);
		$this->meta['params'] = $params;
		$this->meta['contentType'] = $contentType;

		// create curl resource
		$this->ch = curl_init();

		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $readTimeout);

		// 这个选项是为了防止lighttpd返回417错误而加的。参考链接：http://hi.baidu.com/loveyoursmile/blog/item/297fc8bf8e7d560718d81f56.html
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Expect:'));

		// set method
		$resource .= ".{$contentType}";
		$params['_method'] = $method;
		$str_params = Util::encodeArray($params);
		if ($method == self::METHOD_POST || $method == self::METHOD_PUT || $method == self::METHOD_DELETE) {
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, self::METHOD_POST);
			curl_setopt($this->ch, CURLOPT_URL, "http://{$host}/{$resource}");
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $str_params);
		}

		if ($method == self::METHOD_GET || $method == self::METHOD_LIST) {
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, self::METHOD_GET);
			curl_setopt($this->ch, CURLOPT_URL, "http://{$host}/{$resource}?{$str_params}");
		}

		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);

		$this->mch = curl_multi_init();
		curl_multi_add_handle($this->mch, $this->ch);

		do {
			$mrc = curl_multi_exec($this->mch, $this->running);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
	}

	// 检查异步请求是否还在运行
	public function isRunning() {
		curl_multi_exec($this->mch, $this->running);
		return $this->running;
	}

	// 获取异步请求返回内容，阻塞直到有内容返回。
	public function getContent() {
		while ($this->running) {
			$mrc = curl_multi_exec($this->mch, $this->running);
		};
		$content = curl_multi_getcontent($this->ch);

		$contentType = $this->meta['contentType'];
		if ($contentType == 'json') {
			return json_decode($content, true);
		}
		return $content;
	}

	public function getCacheKey() {
		$mark = $this->meta['resource'] . '_' . $this->meta['method'] . '_' . $this->meta['contentType'] . '_' . Util::encodeArray($this->meta['params']);
		return 'rest_proxy_result_cache_' . $mark;
	}

	public function getContentWithCache($block = false, $expireTime = 60) {
		$redisKey = $this->getCacheKey();

		$redis = self::getRedis();

		$content = '';
		if (!$block) {
			$content = $redis->get($redisKey);
		} else {
			while ($this->running) {
				$mrc = curl_multi_exec($this->mch, $this->running);
			};
			if (!$this->running) {
				$content = curl_multi_getcontent($this->ch);
				$redis->setex($redisKey, $expireTime, $content);
			}
		}

		$contentType = $this->meta['contentType'];
		if ($contentType == 'json') {
			return json_decode($content, true);
		}
		return $content;
	}

	public function callMethodLocal($method, $host, $resource, $params, $contentType = "json") {
		require_once dirname(__FILE__) . '/RestDispatcher.class.php';

		global $isLocal;
		$isLocal = true;

		$bakGet = $_GET;
		$bakPost = $_POST;
		$bakRequest = $_REQUEST;

		$_GET = array();
		$_POST = array();
		$_REQUEST = array();

		$url = $resource;
		$parts = parse_url("http://localhost/" . $url);
		if (isset($parts['query'])) {
			$params = explode('&', $parts['query']);
			foreach ($params as $param) {
				$temp = explode('=', $param);
				if ($temp)
					$_GET[$temp[0]] = $temp[1];
			}
		}

		$resourceNameArray = explode('.', $parts['path']);
		$resourceName = $resourceNameArray[0];

		if (!is_file(RESOURCE_PATH . $resourceName . '.class.php'))
			exit;

		require_once RESOURCE_PATH . $resourceName . '.class.php';

		# 生成资源对象，并且调用对应的方法
		$classBame = RestDispatcher::getClassName($resourceName);

		$obj = new $classBame;
		switch ($method) {
			case 'GET':
				$_GET = array_merge($_GET, $params);
				$obj->get();
				break;
			case 'POST':
				$_POST = array_merge($_POST, $params);
				$obj->post();
				break;
			case 'PUT':
				$_POST = array_merge($_POST, $params);
				$obj->put();
				break;
			case 'DELETE':
				$_POST = array_merge($_POST, $params);
				$obj->delete();
				break;
			case 'LIST':
				$_GET = array_merge($_GET, $params);
				$obj->items();
				break;
		}

		$_GET = $bakGet;
		$_POST = $bakPost;
		$_REQUEST = $bakRequest;

		return RestDispatcher::$data;
	}

}