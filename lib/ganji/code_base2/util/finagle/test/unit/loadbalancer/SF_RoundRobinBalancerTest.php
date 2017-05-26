<?php

require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';


class SF_RoundRobinBalancerTest extends PHPUnit_Framework_TestCase {
	
	public function setUp() {
		$node1 = new SF_NodeState();
		$node1->host("192.168.1.1");
		$node1->port(80);
		$node1->weight(5);
		
		$node2 = new SF_NodeState();
		$node2->host("192.168.1.2");
		$node2->port(80);
		$node2->weight(1);
		
		$node3 = new SF_NodeState();
		$node3->host("192.168.1.3");
		$node3->port(80);
		$node3->weight(2);
		
		$this->nodes=array(
				$node1,$node2,$node3
		);
		
		$loadBalancerFactory = new SF_RoundRobinLoadBalancerFactory();
		$this->loadBalancer = $loadBalancerFactory->get();
	}
	
	public function testMatch() {
		$loadBalancer = $this->loadBalancer;
		$selectNode1 = $loadBalancer->match($this->nodes);
// 		var_dump($this->nodes[1]);
		$this->assertEquals($selectNode1->key(),$this->nodes[0]->key());
		
		$selectNode2 = $loadBalancer->match($this->nodes);
// 		var_dump($selectNode2);
		$this->assertEquals($selectNode2->key(),$this->nodes[2]->key());
		
		$selectNode3 = $loadBalancer->match($this->nodes);
		//var_dump($selectNode3);
		$this->assertEquals($selectNode3->key(),$this->nodes[0]->key());
		
		$selectNode4 = $loadBalancer->match($this->nodes);
		//var_dump($selectNode4);
		$this->assertEquals($selectNode4->key(),$this->nodes[0]->key());
		
		$selectNode5 = $loadBalancer->match($this->nodes);
		//var_dump($selectNode5);
		$this->assertEquals($selectNode5->key(),$this->nodes[1]->key());
		
		$selectNode6 = $loadBalancer->match($this->nodes);
		//var_dump($selectNode6);
		$this->assertEquals($selectNode6->key(),$this->nodes[0]->key());
		
		$selectNode7 = $loadBalancer->match($this->nodes);
		//var_dump($selectNode7);
		$this->assertEquals($selectNode7->key(),$this->nodes[2]->key());
		
		$selectNode8 = $loadBalancer->match($this->nodes);
		//var_dump($selectNode8);
		$this->assertEquals($selectNode8->key(),$this->nodes[0]->key());
	}
	
	public function testFail() {
		$max_fails = 3;
		$this->nodes[0]->max_fails=$max_fails;
		
		for($i = 0; $i < $max_fails;$i++) {
			$this->nodes[0]->fail();
		}
		
		$this->assertTrue($this->nodes[0]->current_weight == 0);
		$totalWeight=0;
		foreach($this->nodes as $node) {
			$totalWeight = $totalWeight + $node->weight;
		}
		
		for($i = 0; $i < $totalWeight ; $i++) {
			$select = $this->loadBalancer->match($this->nodes);
			$this->assertNotEquals($select->key(),$this->nodes[0]->key());
		}
		var_dump("before sleep,time is ->".time());
		sleep($this->nodes[0]->fail_timeout + 1);
		var_dump("after sleep,time is ->".time());
		for($i = 0; $i < $totalWeight*5 ; $i++) {
			$select = $this->loadBalancer->match($this->nodes);
			//var_dump($select->resume);
			$this->assertNotNull($select);
		}
		
		$this->nodes[0]->weight($totalWeight);
		//这回必中
		$select = $this->loadBalancer->match($this->nodes);
		$this->assertEquals($select->key(),$this->nodes[0]->key());
	}
	
	public function testFail1() {
		$max_fails = 3;
		//$this->nodes[0]->max_fails=$max_fails;
		/* foreach ($this->nodes as $node) {
			$node ->max_fails = $max_fails;
		} */
		$this->nodes[0]->fails=0;
		$this->nodes[1]->fails=0;
		$this->nodes[2]->fails=0;
		$this->nodes[0]->max_fails=$max_fails;
		$this->nodes[1]->max_fails=$max_fails;
		$this->nodes[2]->max_fails=$max_fails;
		$node1Count = 0;
		$node2Count = 0;
		$node3Count = 0;
		
		for($i=0;$i<10000000;$i++) {
			if($i % 1000 == 0) {
				$rand = rand(0,2);
				//var_dump($rand);
				$this->nodes[$rand]->fail();
			}
			
			$select = $this->loadBalancer->match($this->nodes);
			//var_dump($select->host);
			if($select->host == $this->nodes[0]->host) {
				$node1Count = $node1Count + 1;
			} else if($select->host == $this->nodes[1]->host) {
				$node2Count = $node2Count + 1;
			} else if($select->host == $this->nodes[2]->host) {
				$node3Count = $node3Count + 1;
			}
		}
		
		var_dump("node1 select count=".$node1Count);
		var_dump("node2 select count=".$node2Count);
		var_dump("node3 select count=".$node3Count);
		$this->assertTrue($node1Count > 0);
		$this->assertTrue($node2Count > 0);
		$this->assertTrue($node3Count > 0);
	}
}
?>