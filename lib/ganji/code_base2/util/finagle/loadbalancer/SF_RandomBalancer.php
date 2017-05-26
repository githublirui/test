<?php
require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancer.php';
require_once FINAGLE_BASE . '/loadbalancer/SF_RoundRobinBalancer.php';
/**
 * 随机选择一个地址
 * @author hexiaohua
 *
 */
	class SF_RandomLoadBalancer implements SF_LoadBalancer{
		/**
		 * @param  $addrs为地址数组,数组里边的对象为SF_NodeState
		 * @see SF_LoadBalancer::match()
		 */
		public function  match($nodes) {
			//暂时random也是用加权轮询算法。
			$len = count($nodes);
			if($len == 1) {
				return current($nodes);
			}
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
					
					//$node->current_weight = $node->current_weight  + $node->effective_weight;
					$total = $total + $node->effective_weight;
				}
				
				$randWeight = rand(1, $total);
				$tmpWeight = 0;
				foreach ($nodes as $node) {
					if($node->down()) {
						continue;
					}
						
					$maxFail = $node->max_fails  > 0 && $node->fails >= $node->max_fails;
					$atFailTime=$now - $node->checked <= $node->fail_timeout;
					if($maxFail && $atFailTime) {
						continue;
					}
					
					$tmpWeight = $node->effective_weight + $tmpWeight;
					if($node->effective_weight < $node ->weight) {
						$node->effective_weight = $node->effective_weight + 1;
						if($node->effective_weight > $node ->weight) {
							$node->effective_weight = $node ->weight;
						}
					}
					
					if($tmpWeight >= $randWeight) {
						$best = $node;
						break;
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
			
			//$best->current_weight = $best->current_weight - $total;
			$best->checked = $now;
			
			return $best;
		}
	}
?>