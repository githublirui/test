<?php
/**
 *
 * User: tailor
 * Date: 14-3-19 Time: 下午2:43
 *
 * @update: 2014-04-28  赵卫国  基于整体修改后的SF_HttpRequest修改SF_HttpClient
 * @update: 2014-05-09  赵卫国  整体修改使成为SF框架的http client
 *
 *
 * @brief: SF框架的http客户端
 *
 * TODO: support http1.1 reuse connection
 */

require_once FINAGLE_BASE . "/client/SF_HttpRequest.php";
require_once FINAGLE_BASE . "/client/http/SF_CurlHttpRequest.php";  // 两个httpRequest的实现类
require_once FINAGLE_BASE . "/client/http/SF_SocketHttpRequest.php";

require_once FINAGLE_BASE . "/exception/SF_Exception.php";

class SF_HttpClient
{

    //连接超时、连接重试参数
    const HTTP_CONNECT_TIMEOUT_DEFAULT = 0;
    const HTTP_READ_TIMEOUT_DEFAULT = 0;
    const HTTP_RETRY_DEFAULT = 0;


    /**
     * @param string $host  域名/ip
     * @param string $uri   资源
     * @param int $connectTimeout 连接超时(毫秒, 默认为0)
     * @param int $readTimeout 读取超时(毫秒, 默认为0)
     * @param int $retry 重试次数(默认为0)
     */
    public function __construct($host,
                                $port,
                                $connTimeout=self::HTTP_CONNECT_TIMEOUT_DEFAULT,
                                $readTimeOut=self::HTTP_READ_TIMEOUT_DEFAULT,
                                $retry=self::HTTP_RETRY_DEFAULT) {
        $this->host = $host;
        $this->port = $port;
        $this->connTimeout = $connTimeout;
        $this->readTimeOut = $readTimeOut;
        $this->retry = $retry;
        //$this->path = $path;
    }

    public function connect() {
    }

    public function execute(SF_HttpRequest $request) {
        try {
            $httpType = $request->getClientType(); //得到client请求类型
            if($httpType == SF_HttpRequest::HTTP_CLIENT_TYPE_SOCKET) {
                //socket方式请求http
                $socketRequest = new SF_SocketHttpRequest(
                                    $this->host,
                                    $this->port,
                                    $this->connTimeout,
                                    $this->readTimeOut,
                                    $request->getUri(),
                                    $request->getMethod(),
                                    $request->getSchema(),
                                    $request->getVersion(),
                                    $request->getUserAgent(),
                                    $request->getAccept(),
                                    $request->getContentType(),
                                    $request->getPostStr());
                $resp_str = $socketRequest->request();


            } elseif($httpType == SF_HttpRequest::HTTP_CLIENT_TYPE_CURL) {
                // @todo curl方式执行http请求
                $resp_str="this type will be implemented later!";
                throw new SF_NoSuchHttpClientException($httpType);

            }
        }
        catch(Exception $e) {
            throw $e;
        }
        return $resp_str;
    }

    public function close() {
    }
}
