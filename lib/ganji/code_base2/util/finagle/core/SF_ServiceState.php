<?php
/**
 * 本类实现的功能是存储Service的状态。本类经过序列化可以存到apc缓存。
 * @author xingwenge
 */
require_once FINAGLE_BASE.'/regcenter/SF_ServiceLocationClient.php';
require_once FINAGLE_BASE. '/core/SF_NodeState.php';
require_once FINAGLE_BASE. '/core/SF_NodeStates.php';

class SF_ServiceState {
	/**
	 * 全局的状态
	 * @var array 
	 * 数据结构例如：
	 * array(
	 *    serviceName=>SF_NodeStates
	 * )
	 */
	private static $all_states = array();

    /**
     * 静态方法，从这里拿到$serviceName的一个服务地址
     * @param SF_ClientBuilder $builder
     * @return array
     * @throws LengthException
     */
    public function getState(SF_ClientBuilder $builder) {
		// lookup all_states
		$serviceName = $builder->serviceName;
		if(!array_key_exists($serviceName, self::$all_states)) {
            self::$all_states[$serviceName] = $this->loadStates($builder);
		}

        /*
        if(is_object(self::$all_states[$serviceName])) {
            throw new SF_NoRemoteServiceException("all_states[serverName] is not an object");
        }
        */
        // models
        $models = self::$all_states[$serviceName]->getNodeStates();

        $len = count($models);
        if($len < 1) {
        	throw new SF_NoEffectiveRemoteServiceException("no remote server effective!");
        }
        
        // balancer.
		$loadBalancer = $builder->loadBalancer;
		return $loadBalancer->match(array_values($models));
	}
	
	public static function removeLocalStates($serviceName) {
		if(array_key_exists($serviceName, self::$all_states)) {
			unset(self::$all_states[$serviceName]);
		}
	}

    private static $INSTANCE;
    public static function INSTANCE() {
        if(self::$INSTANCE === null) {
            self::$INSTANCE = new self();
        }

        return self::$INSTANCE;
    }

	private function __construct() {
	}

    /**
     * @param SF_ClientBuilder $builder
     * @return SF_NodeStates
     */
    private function loadStates(SF_ClientBuilder $builder) {
        // load_from_apc 如果apc中没有，则从注册中心去取，取完放到$all_states中，并save到apc中去
        $apc_key = self::getApcKey($builder->serviceName);
        if(apc_exists($apc_key)) {
            $nodeStates = self::readApc($apc_key);
        }
        else {
            $nodeStates = $this->readRegCenter($builder);
            $this->writeApc($apc_key, $nodeStates, $nodeStates->getApcStoreTTL());
        }

        return $nodeStates;
    }

    public function loadStatesFromApcBykey(SF_ClientBuilder $builder, $node_key) {
        $apc_key = self::getApcKey($builder->serviceName);
        if(apc_exists($apc_key)) {
            if($nodeStates = self::readApc($apc_key)) {
                $models = $nodeStates->getNodeStates();
                if(array_key_exists($node_key, $models)) {
                    return $models[$node_key];
                }
            }
        }

        return false;
    }
    
    public static function setHostsState($serviceName, $hosts) {
    	$nodeStates = new SF_NodeStates();
    	$nodeStates->setCreateTime(SF_Duration::fromSeconds(time()));

    	foreach ($hosts as $hostModel) {
    		$dataModel = new SF_NodeState();
    		$dataModel->host($hostModel['host']);
    		$dataModel->port($hostModel['port']);
    		$dataModel->weight($hostModel['weight']);
    		$nodeStates->setNodeState($dataModel->key(), $dataModel);
    	}
    	self::setStates($serviceName, $nodeStates);
    }
    
    public static function removeState($serviceName) {
    	unset(self::$all_states[$serviceName]);
    }

    public static function setStates($serviceName, $nodeStates) {
    	self::$all_states[$serviceName] = $nodeStates;
    }
    
    /**
     * 获取apc缓存名称
     * @param string $name
     * @return string
     */
    private static function getApcKey($name) {
        return urlencode('Finagle_'. $name);
    }
	
	/**
	 * 将一个服务状态写入到apc中
	 * @param SF_NodeState  $state 
	 */
	private function writeApc($key, $value, $expireTime) {
		apc_store($key, $value, $expireTime);
	}
	
	/**
	 * 从apc中获取数据
	 *  @return SF_NodeState 
	 */
	private static function readApc($apc_key) {
		return apc_fetch($apc_key);
	}
	
	/**
	 * 从注册中心获取数据
     * @return SF_NodeStates
	 */
	private function readRegCenter(SF_ClientBuilder $builder) {
        return SF_ServiceLocationClient::INSTANCE()->getServiceStateDataModels($builder);
	}

    /**
     * 节点变更通知，需要存储状态
     * @param SF_ClientBuilder $builder
     * @param SF_NodeState $stateModel
     */
    public static function nodeStateModifyNotify(SF_ClientBuilder $builder, SF_NodeState $stateModel) {
        //更新 APC
        self::INSTANCE()->updateAPCNodeState($builder, $stateModel);

        //更新静态变量 all_steates
        self::INSTANCE()->updateAllStatesNodeState($builder, $stateModel);
    }

    private function updateAPCNodeState(SF_ClientBuilder $builder, SF_NodeState $newNodeState) {
        $apcKey = self::getApcKey($builder->serviceName);
        if(apc_exists($apcKey)) {
            if($obj = $this->readApc($apcKey)) {
                if($nodeStates = $obj->getNodeStates()) {
                    if(array_key_exists($newNodeState->key(), $nodeStates)){
                        $obj->setNodeState($newNodeState->key(), $newNodeState);

                        $expire_time = $obj->getApcStoreTTL() - SF_Duration::timeIntervalInSeconds(SF_Duration::fromSeconds(time()), $obj->getCreateTime());
                        if($expire_time > 0) {
                            $this->writeApc($apcKey, $obj, $expire_time);
                        }
                    }
                }
            }
        }
    }

    private function updateAllStatesNodeState(SF_ClientBuilder $builder, SF_NodeState $newNodeState) {
        if(array_key_exists($builder->serviceName, self::$all_states)){
            self::$all_states[$builder->serviceName]->setNodeState($newNodeState->key(), $newNodeState);
        }
    }
}