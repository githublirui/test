<?php
require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancer.php';
class SF_ConsistentHashBalancer implements SF_LoadBalancer{
	private $key;
	
	function __construct($key) {
		$this->key=$key;
	}
	
	/**
	 * 目前不提供实现，直接指向random的实现。真正的一致性hash @see util/ConsistentHashing.php
	 */
	function  match($addrs) {
			$loadBalancer = new SF_RandomLoadBalancer();
			return $loadBalancer->match($addrs);
	}
}
?>