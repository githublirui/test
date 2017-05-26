<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

class SF_RandomBalancerTest extends PHPUnit_Framework_TestCase {
	
	public function testMatch() {
		$state1 = new SF_NodeState();
		$state1->host("192.168.1.1");
		$state1->port(80);
		$state1->weight(1);
		
		$state2 = new SF_NodeState();
		$state2->host("192.168.1.2");
		$state2->port(80);
		$state2->weight(1);
		
		$states=array(
				$state1,$state2
				);
		
		$loadBalancerFactory = new SF_RandomLoadBalancerFactory();
		$loadBalancer = $loadBalancerFactory->get();
		$cntArr = array();
		foreach ($states as $state) {
			$cntArr[$state->host] = 0;
		}
		
		$maxLoop = 10000000;
		$begin = time();
		for($i=0;$i < $maxLoop;$i++) {
			$state = $loadBalancer->match($states);
			//设定2%的几率会失败。
			$rand = rand(0, 100);
			if($rand <= 2) {
				$state->fail();
			}
			
			$cntArr[$state->host] = $cntArr[$state->host] + 1;
		}
		$end = time();
		var_dump("time cost =".($end - $begin));
		
		$maxVisit = $maxLoop * 0.55;
		$minVisit = $maxLoop * 0.45;
		foreach($cntArr as $key=>$value) {
			var_dump($key."=====>".$value);
		}
		
		foreach($cntArr as $key=>$value) {
			$this->assertTrue($value > $minVisit);
			$this->assertTrue($value < $maxVisit);
		}
	}
}
?>