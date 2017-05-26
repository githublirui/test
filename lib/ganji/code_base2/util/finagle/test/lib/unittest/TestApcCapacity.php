<?php
require_once dirname(__FILE__).'/../SF_Bootstrap.php';

class TestApcCapacity extends PHPUnit_Framework_TestCase {
    private $begin_time;
    private $rw_times; //读写总共次数

    public function setUp() {
        $this->begin_time = time();
        $this->rw_times = 1000000;
    }

    public function testRun() {
        for($i = $this->rw_times; $i>0; $i--) {
            $apc_key = urlencode('Finagle_test');

            //write
            apc_store($apc_key, $this->newSFNodeStates(), 60);

            //read
            apc_fetch($apc_key);
        }
    }

    public function tearDown() {
        $time_diff = $this->timeDiff($this->begin_time, time());
        echo 'Total spend:'. $time_diff. '秒'. PHP_EOL;
    }

    private function timeDiff($time1, $time2) {
        return $time2 - $time1;
    }

    private function newSFNodeStates() {
        $num = rand(1,255);
        $addressStr = '[{"name":"/soa/testserivce","host":"192.168.1.'. $num .'","port":"8000","weight":"0"},{"name":"/soa/testserivce","host":"192.168.1.2","port":"8001","weight":"0"}]';
        $nodeStates = new SF_NodeStates();

        foreach (json_decode($addressStr, true) as $v) {
            $nodeState = new SF_NodeState();
            $nodeState->host($v['host']);
            $nodeState->port($v['port']);
            $nodeState->weight($v['weight']);

            $nodeStates->setNodeState($nodeState->key(), $nodeState);
        }

        $nodeStates->setCreateTime(SF_Duration::fromSeconds(time()));

        return $nodeStates;
    }
}