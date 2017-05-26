<?php
require_once FINAGLE_BASE. '/regcenter/SF_proxy_types.php';
require_once FINAGLE_BASE. '/regcenter/SF_RegistryProxy.php';
require_once FINAGLE_BASE. '/core/SF_NodeStates.php';



class SF_ServiceLocationClient {
    private static $_INSTANCE;

    public static function INSTANCE() {
        if(self::$_INSTANCE === null) {
            self::$_INSTANCE = new self();
        }

        return self::$_INSTANCE;
    }

    private $_serviceCenter_ip;
    private $_serviceCenter_port;
    private $_thrift_client;
    private $_thrift_transport;

    private function __construct() {
        if( isset($GLOBALS['SF_AGENT'])) {
            list($host,$port) = explode(':', $GLOBALS['SF_AGENT']);
        }
        else {
            $host = "127.0.0.1";
            $port = '9009';
        }

        $this->_serviceCenter_ip = $host;
        $this->_serviceCenter_port = $port;


        $this->_initThriftConnect();
    }


    /**
     * @param SF_ClientBuilder $builder
     * @return SF_NodeStates
     * @throws SF_ServiceLocationClientException
     */
    public function getServiceStateDataModels(SF_ClientBuilder $builder) {
        //policy
        $policy = self::_getClientRetryPolicy($builder);

        while($policy->getRetryGetTimes() > 0) {
            try {
                if($addressStr = $this->_thrift_client->get($builder->serviceName)) {
                    //'[{"name":"/soa/testserivce","host":"192.168.1.1","port":"8000","weight":"0"},{"name":"/soa/testserivce","host":"192.168.1.2","port":"8001","weight":"0"},{"name":"/soa/testserivce","host":"192.168.1.3","port":"8002","weight":"0"}]';
                    $nodeStates = new SF_NodeStates();

                    foreach (json_decode($addressStr, true) as $v) {
                        $nodeState = new SF_NodeState();
                        $nodeState->host($v['host']);
                        $nodeState->port($v['port']);
                        $nodeState->weight($v['weight']);

                        $nodeStates->setNodeState($nodeState->key(), $nodeState);
                    }

                    $nodeStates->setCreateTime(SF_Duration::fromSeconds(time()));

                    return $nodeStates;
                }
                else {
                    throw new SF_ServiceLocationClientException('Service invalid!');
                }
            }
            catch(TTransportException $e) {
                Logger::logWarn($e->getMessage(), 'finagle.ttransportexception');

                $policy->setRetryGetTimes($policy->getRetryGetTimes()-1);
                usleep($policy->getRetryInterval());
            }
            catch(TException $e) {
                if($policy->getRetryConnectTimes() > 0) {
                    Logger::logWarn($e->getMessage(), 'finagle.texception');

                    $policy->setRetryConnectTimes($policy->getRetryConnectTimes()-1);
                    usleep($policy->getRetryInterval());

                    $this->_reInitThriftConnect();
                    return $this->getServiceStateDataModels($builder);

                }
                else {
                    throw new SF_ServiceLocationClientException($e);
                }
            }
            catch(Exception $e) {
                throw new SF_ServiceLocationClientException($e);
            }
        }
    }

    /**
     * @param SF_ClientBuilder $builder
     * @return SF_ServiceLocationClientRetryPolicy
     */
    private static function _getClientRetryPolicy(SF_ClientBuilder $builder) {
        static $clientPolicyList = array(); //[serviceName => obj]

        $serviceName = $builder->serviceName;
        if(!array_key_exists($serviceName, $clientPolicyList)) {
            $retryPolicy = $builder->retryPolicy;

            $obj = new SF_ServiceLocationClientRetryPolicy();
            $obj->setRetryConnectTimes($retryPolicy->times());
            $obj->setRetryGetTimes($retryPolicy->times());
            $obj->setRetryInterval($retryPolicy->interval());

            $clientPolicyList[$serviceName] = $obj;
        }

        return $clientPolicyList[$serviceName];
    }

    /**
     * 初始化thrift连接
     */
    private function _initThriftConnect() {
        //connect
        $socket = new TSocket($this->_serviceCenter_ip, $this->_serviceCenter_port);
        $socket->setSendTimeout(500);
        $socket->setRecvTimeout(500);
        $this->_thrift_transport = new TFramedTransport($socket);
        $protocol = new TBinaryProtocol($this->_thrift_transport);
        $this->_thrift_transport->open();
        $this->_thrift_client = new RegistryProxyClient($protocol);
    }

    /**
     * 重新初始化thrift连接
     */
    private function _reInitThriftConnect() {
        if($this->_thrift_transport) {
            $this->_thrift_transport->close();
        }

        $this->_initThriftConnect();
    }

    public function __destruct() {
        if($this->_thrift_transport) {
            $this->_thrift_transport->close();
        }
    }
}

/**
 * Class SF_ServiceLocationClientRetryPolicy
 * 重试策略，对SF_ServiceLocationClientRetryPolicy封装
 */
class SF_ServiceLocationClientRetryPolicy{
    private $_retryConnectTimes; //重新连接次数
    private $_retryGetTimes; //重新获取次数
    private $_retryInterval; //时间间隔

    /**
     * @param mixed $retryConnectTimes
     */
    public function setRetryConnectTimes($retryConnectTimes) {
        $this->_retryConnectTimes = $retryConnectTimes;
    }

    /**
     * @return mixed
     */
    public function getRetryConnectTimes() {
        return $this->_retryConnectTimes;
    }

    /**
     * @param mixed $retryGetTimes
     */
    public function setRetryGetTimes($retryGetTimes) {
        $this->_retryGetTimes = $retryGetTimes;
    }

    /**
     * @return mixed
     */
    public function getRetryGetTimes() {
        return $this->_retryGetTimes;
    }

    /**
     * @param mixed $retryInterval
     */
    public function setRetryInterval($retryInterval) {
        $this->_retryInterval = $retryInterval;
    }

    /**
     * @return mixed
     */
    public function getRetryInterval() {
        return $this->_retryInterval;
    }
}