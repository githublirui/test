<?php
	require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancerFactory.php';
	require_once FINAGLE_BASE . '/loadbalancer/SF_ConsistentHashBalancer.php';
	class SF_ConsistentHashBalancerFactory implements SF_LoadBalancerFactory {
		private $key;
		
		/**
		 * 一致性hash产生hash的key
		 * @param unknown $key
		 */
		function __construct($key) {
			$this->key = $key;
		}
		
		function get() {
			return new SF_ConsistentHashBalancer($this->key);
		}
	}