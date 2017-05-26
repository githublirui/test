<?php


require_once FINAGLE_BASE . "/exception/SF_Exception.php";


class SF_Service {

	private $clientBuilder;
	/**
	 * 当前连接保存下来。
	 * @var unknown
	 */
	private $current_remote_client;
	/**
	 * 当前被选中的远程服务节点。
	 * @var unknown
	 */
	private $current_node;

	function __construct($clientBuilder) {
		$this->clientBuilder=$clientBuilder;
	}
	/**
	 * 调用流程
	 * 调用远程接口，如果接口出错，如果出现Exception，则通知SF_ServiceState，
	 * 然后根据retry策略，如果不能retry，则抛出异常。否则进行retry
	 * @param  $method
	 * @param mixed $args
	 * @throws SF_ServiceLocationClientException BadMethodCallException 
	 * SF_SocketException   SF_SocketTimeoutException
	 */
	function __call($method, $args) {
        $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.total'); // 统计次数
        $timer = $this->clientBuilder->stats->timingStart($this->getSimpleService() . '.service_call');

		$retryTimes = $this->clientBuilder->retryPolicy->times();
		$retryInterval = $this->clientBuilder->retryPolicy->interval();

		$clientFactory= $this->clientBuilder->getClientFactory();



		for($i = 0; $i < $retryTimes; $i++) {
            try{
                if(!$this->current_remote_client) {
                    $this->current_node = SF_ServiceState::INSTANCE()->getState($this->clientBuilder);
                    //从client缓存中检查是否有client，如果没有，则创建一个。
                    $this->current_remote_client = $clientFactory->getClient(
                                                                $this->clientBuilder,
                                                                $this->current_node->host,
                                                                $this->current_node->port
                                                                );
                }
			
				$result =  call_user_func_array(array($this->current_remote_client, $method), $args );
				if($this->current_node) {
					$this->current_node->success();
					
					if($this->current_node->resume) {
						//恢复apc中node的状态
						$this->current_node->setResume(false);
						SF_ServiceState::nodeStateModifyNotify($this->clientBuilder, $this->current_node);
					}
				}
                $this->clientBuilder->stats->timingEnd($timer); // 统计使用时间
				return $result;
			} catch (BadMethodCallException $e) {
                $this->_logError($e->getMessage());
                $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.error'); // 错误统计次数

                //销毁client
				$this->closeCurrentClient();
				throw $e;
			} catch(SF_FatalException $e) {
                $this->_logError($e->getMessage());
                $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.error'); // 错误统计次数

                throw $e;
            } catch (SF_NormalException $e) {  // timeout类异常需要进行重试
                $this->_logWarn($e->getMessage());
                $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.warning'); // 错误统计次数

                if($this->current_node) {
					$node_key = $this->current_node->key();
					$nodeAtApc = SF_ServiceState::INSTANCE()->loadStatesFromApcBykey($this->clientBuilder, $node_key);
					//瞬时读出，瞬时写入。
					if($nodeAtApc) {
						$nodeAtApc->fail();
						SF_ServiceState::nodeStateModifyNotify($this->clientBuilder, $nodeAtApc);
					}
					//SF_ServiceState::fail($this->clientBuilder, $this->current_node, $e);
				}

				$this->closeCurrentClient();

				//如果失败次数已经到了，则抛出异常
				if($retryTimes > 0 && $i === $retryTimes - 1) {
                    $this->_logError($e->getMessage());
                    $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.error'); // 错误统计次数

                    throw $e;
				}
				if($retryInterval > 0) {
                    $this->_logError($e->getMessage());
                    $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.error'); // 错误统计次数

                    sleep($retryInterval);
				}
				continue;
			} catch(Exception $e) {  // 其他未知错误，直接招聘异常
                $this->_logError($e->getMessage());
                $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.error'); // 错误统计次数
                throw $e;

            }
		}
	}

    private function getSimpleService() {
        return $this->clientBuilder->getServiceName();
    }

        /**
     * @param $msg
     * @brief 判断是否存在Logger类，并进行error日志输出
     */
    private function _logError($msg="") {

        if (!class_exists('Logger') || !method_exists('Logger', 'logError')) {
            return;
        }
        $serviceName =$this->clientBuilder->getServiceName();
        Logger::logError($msg, 'finagle.' . $serviceName);
    }

    /**
     * @param $msg
     * @brief 判断是否存在Logger类，并进行warning日志输出
     */
    private function _logWarn($msg="") {

        if (!class_exists('Logger') || !method_exists('Logger', 'logWarn')) {
            return;
        }
        $serviceName =$this->clientBuilder->getServiceName();
        Logger::logWarn($msg, 'finagle.' . $serviceName);
    }



	private function closeCurrentClient() {
		if($this->current_remote_client) {
			try{
				$this->current_remote_client->close();
			} catch (Exception $e){
                $this->_logError($e->getMessage());
                $this->clientBuilder->stats->count($this->getSimpleService() . '.service_call.error'); // 错误统计次数

            }
		}
		$this->current_remote_client=null;
	}
	
	function  __set($field,$value) {
		$this->$field = $value;
	}
	
	function __destruct() {
		$this->closeCurrentClient();
		//需要删掉本地传入的
		if($this->clientBuilder->hosts) {
			SF_ServiceState::removeLocalStates($this->clientBuilder->serviceName);
		}
	}


}