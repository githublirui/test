<?php
	require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancerFactory.php';
	require_once FINAGLE_BASE . '/loadbalancer/SF_RoundRobinBalancer.php';
	class SF_RoundRobinLoadBalancerFactory implements SF_LoadBalancerFactory {
		
		public function get() {
			return new SF_RoundRobinBalancer();
		}
	}
?>