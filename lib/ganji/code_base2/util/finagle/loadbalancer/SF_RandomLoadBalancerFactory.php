<?php
	require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancerFactory.php';
	require_once FINAGLE_BASE . '/loadbalancer/SF_RandomBalancer.php';
	class SF_RandomLoadBalancerFactory implements SF_LoadBalancerFactory {
		
		function get() {
			return new SF_RandomLoadBalancer();
		}
	}
?>