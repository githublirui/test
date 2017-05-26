<?php
	require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancer.php';
	class SF_RoundRobinBalancer implements SF_LoadBalancer{
		public function  match($nodes) {
			//表示还没有选择过，这时候就random选择一台机器。
			//如果在一个context里边调用多次，在下次顺序选择机器
			$len = count($nodes);
			if($len == 1) {
				return current($nodes);
			}
			$node=null;
			$best = null;
			
			$now = time();
			$total = 0;
			
			while($best == null) {
				foreach ($nodes as $node) {
					if($node->down()) {
						continue;
					}
					
					$maxFail = $node->max_fails  > 0 && $node->fails >= $node->max_fails;
					$atFailTime=$now - $node->checked <= $node->fail_timeout;
					if($maxFail && $atFailTime) {
						continue;
					}
					
					$node->current_weight = $node->current_weight  + $node->effective_weight;
					$total = $total + $node->effective_weight;
					
					if($node->effective_weight < $node ->weight) {
						$node->effective_weight = $node->effective_weight + 1;
					}
					
					if($best == null || $node->current_weight > $best->current_weight) {
						$best = $node;
					}
				}
				
				//表明所有的节点都挂掉了。需要重新恢复，然后再选择
				if($best == null) {
					foreach ($nodes as $node) {
						$node->resume();
					}
				}
			}
			
/* 			if($best == null) {
				return false;
			} */
			
			$best->current_weight = $best->current_weight - $total;
			$best->checked = $now;
			
			return $best;
		}
	}
?>