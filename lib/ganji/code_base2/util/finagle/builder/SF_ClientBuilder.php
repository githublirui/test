<?php
/**
 * 
 * Provides a class for building clients.   as so
 *		thrift示例：
 *		$builder = new SF_ClientBuilder();
 * 		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
 *					  ->clientFactory(new SF_ThriftClientFactory("HelloClient"))
 *					  ->loadBalance(new SF_RandomLoadBalancerFactory())
 *					  ->retries(3)
 *					  ->destName($this->serviceName);
 *		
 *		$client=$builder->build();
 *
 *		http示例：
 *		$builder = new SF_ClientBuilder();
 *		$serviceName="/soa/services/hello";
 *		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
 *						->clientFactory(new SF_HttpClientFactory())
 *						->loadBalance(new SF_RandomLoadBalancerFactory())
 *						->httpPath("/a/b")
 *						->destName($serviceName)
 *						->retries(3);
 *		$client=$builder->build();
 *
 * The `ClientBuilder` requires the definition of  `hostConnectionLimit`.
 *  In Scala, these are statically type
 * checked, and in Java the lack of any of the above causes a runtime
 * error.
 *
 */
require_once FINAGLE_BASE.'/core/SF_RetryPolicy.php';
require_once FINAGLE_BASE."/regcenter/SF_ServiceLocationClient.php";
require_once FINAGLE_BASE."/util/SF_Duration.php";
require_once FINAGLE_BASE.'/core/SF_Service.php';
require_once FINAGLE_BASE.'/core/SF_ServiceState.php';
require_once FINAGLE_BASE.'/util/SF_UUID.php';
require_once FINAGLE_BASE.'/loadbalancer/SF_RoundRobinBalancer.php';
require_once FINAGLE_BASE.'/stats/SF_NotStats_Factory.php';

class SF_ClientBuilder {
    /**
     * 做负载均衡的类
     * @var unknown
     */
    private $loadBalancer;
    /**
     * 协议解析类
     * @var unknown
     */
    private $clientFactory;
    /**
     * 重试策略
     * @var unknown
     */
    private $retryPolicy;
    /**
     * clientBuilder实例
     * @var unknown
     */
    private $clientBuilder;
    /**
     * 服务名称
     * @var unknown
     */
    private $serviceName;
    /**
     * SF_Duration
     */
    private $timeout;
    /**
     * tcp连接超时时间
     * @var unknown
     */
    private $tcpConnectTimeout;
    /**
     * 连接限制
     * @var unknown
     */
    private $hostConnectionLimit;
    /**
     * http路径，不包含host以及port
     * @var unknown
     */
    private $httpPath;
    /**
     * 直接传入hosts，不走注册中心
     * @var array
     */
    private $hosts;

    private $stats;  // stats数据收集


    private function apply() {
        return new ClientBuilder();
    }

    function get() {
        return apply();
    }

    function build() {
        //assert($this->loadBalancer);
        //assert($this->serviceName);
        //设置连接超时默认值
        if(!$this->tcpConnectTimeout) {
            $this->tcpConnectTimeout = new SF_Duration(1000, SF_Duration::UNIT_MILLISECONDS);
        }

        //设置读写默认超时时间
        if(!$this->timeout) {
            $this->timeout = new SF_Duration(750, SF_Duration::UNIT_MILLISECONDS);
        }

        if(!$this->retryPolicy) {
            $this->retryPolicy = new SF_RetryPolicy(
                    SF_RetryPolicy::$DEFAULT_RETRY_TIMES,
                    SF_RetryPolicy::$DEFAULT_INTERVAL
                    );
        }

        if(!$this->stats) {
            $this->stats = SF_NotStats_Factory::get(1);
        }

        //如果hosts不为空，则直接将hosts放到SF_ServiceState::state中去
        if($this->hosts) {
            //防止用户错误而覆盖注册中心的配置或者其他用户的配置。
            //所以这里讲serviceName加上uuid，这样重名概率几乎为0
            $this->serviceName=$this->serviceName."-".SF_UUID::guid();
            SF_ServiceState::setHostsState($this->serviceName, $this->hosts);
        }

        //默认用加权轮询
        if(!$this->loadBalancer) {
            $this->loadBalancer = new SF_RoundRobinBalancer();
        }

        return new SF_Service($this);
    }

    /**
     * The logical destination of requests dispatched through this
     * client.
     */
    function destName($name) {
        $this->serviceName=$name;
        return $this;
    }

    /**
     * @return 返回当前服务的服务名(去掉uuid的)
     */
    function getServiceName() {
        $simpleService = explode('-', $this->serviceName);
        return $simpleService[0];
    }

    // 数据监控功能
    function stats($stats) {
        $this->stats = $stats;
        return $this;
    }

    /**
     * @param SF_Duration $timeout
     * @brief 因语义原因, 用此方法被responseTimeout/1方法代替
     */
    function timeout(SF_Duration $timeout) {
        assert( is_a( $timeout , "SF_Duration" ));
        $this->timeout = $timeout;
        return $this;
    }
    /**
     * @param SF_Duration $resTimeout
     * @brief 设定响应超时时间
     */
    function responseTimeout(SF_Duration $resTimeout) {
        assert( is_a( $resTimeout , "SF_Duration" ));
        $this->timeout = $resTimeout;
        return $this;
    }

    /**
     * @param SF_Duration $timeout
     * @brief 设定连接超时时间
     */
    function tcpConnectTimeout(SF_Duration $timeout) {
        $this->tcpConnectTimeout=$timeout;
        return $this;
    }

    /**
     * 传入clientFactory。
     * @param $clientFactory
     */
    function clientFactory($clientFactory) {
        $this->clientFactory=$clientFactory;
        return $this;
    }

    function getClientFactory() {
        return $this->clientFactory;
    }
    /**
     * 负责均衡策略
     * @param $factory
     */
    function loadBalance($factory) {
        $loadBalancer = $factory->get();
        $this->loadBalancer=$loadBalancer;
        return $this;
    }

    function retries($times) {
        $retryPolicy = new SF_RetryPolicy($times, SF_RetryPolicy::$DEFAULT_INTERVAL);
        $this->retryPolicy($retryPolicy);
        return $this;
    }

    function retryPolicy($retryPolicy) {
            $this->retryPolicy=$retryPolicy;
            return $this;
    }

    function hostConnectionLimit($hostConnectionLimit) {
        $this->hostConnectionLimit=$hostConnectionLimit;
        return $this;
    }

    function getHttpPath() {
        if($this->httpPath == null) {
            return "";
        }

        return $this->httpPath;
    }

    /**
     * 必须传入array(
     * 		array("host"=>"127.0.0.1","port"=>80,"weight"=>1)
     * )
     * weight是可选属性，如果没有传，默认为1
     * @param array $hosts
     */
    function hosts(Array $hosts) {
        $this->hosts = $hosts;
        return $this;
    }

    function httpPath($path) {
        $this->httpPath=$path;
        return $this;
    }

    function  __get($name) {
        return $this->$name;
    }
}
