<?php
/**
 * Created by PhpStorm.
 * User: zhaoweiguo
 * Date: 14-5-8
 * Time: PM1:24
 */


require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . '/loadbalancer/SF_WeightRoundRobinLoadBalancer.php';
require_once FINAGLE_BASE . '/loadbalancer/SF_WeightRoundRobinLoadBalancerFactory.php';

class SF_WeightRoundRobinLoadBalancerTest extends PHPUnit_Framework_TestCase {

    public function setUp() {

        ini_set('apc.enable_cli', '1');

        $node1 = new SF_NodeState();
        $node1->host("192.168.1.1");
        $node1->port(80);
        $node1->weight(3);

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

        apc_delete(SF_WeightRoundRobinLoadBalancer::CURRENT_NUMBER);
        apc_delete(SF_WeightRoundRobinLoadBalancer::CURRENT_WEIGHT);


        $loadBalancerFactory = new SF_WeightRoundRobinLoadBalancerFactory();
        $this->loadBalancer = $loadBalancerFactory->get();


    }

    public function testMatch() {
        $loadBalancer = $this->loadBalancer;

/*
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
        var_dump($loadBalancer->match($this->nodes)->key());
*/

        $node1 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node1->key(), $this->nodes[0]->key());

        $node2 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node2->key(), $this->nodes[0]->key());

        $node3 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node3->key(), $this->nodes[2]->key());

        $node4 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node4->key(), $this->nodes[0]->key());

        $node5 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node5->key(), $this->nodes[1]->key());

        $node6 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node6->key(), $this->nodes[2]->key());

        // 6次是一个循环
        $node7 = $loadBalancer->match($this->nodes);
        $this->assertEquals($node7->key(), $this->nodes[0]->key());

    }

}
?>