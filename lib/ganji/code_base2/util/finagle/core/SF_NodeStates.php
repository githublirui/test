<?php
/**
 * 服务所有节点的信息。
 * @author xingwenge
 */
class SF_NodeStates {
    private $_nodeStates; //[key=>SF_NodeState]
    private $_createTime; //创建时间 SF_Duration
    private $_apcStoreTTL = 60; //apc缓存SF_NodeStates的时间

    /**
     * @return [SF_NodeState]
     */
    public function getNodeStates() {
        return $this->_nodeStates;
    }

    /**
     * 设置节点
     * @param null $key
     * @param SF_NodeState $nodeState
     */
    public function setNodeState($key, SF_NodeState $nodeState) {
            $this->_nodeStates[$key] = $nodeState;
    }

    /**
     * 设置创建时间
     * @param $time
     */
    public function setCreateTime(SF_Duration $duration) {
        $this->_createTime = $duration;
    }

    /**
     * @return SF_Duration
     */
    public function getCreateTime() {
        return $this->_createTime;
    }

    /**
     * 设置apc缓存时间(秒）
     * @param int $second
     */
    public function setApcStoreTTL($second) {
        $this->_apcStoreTTL = $second;
    }

    /**
     * @return int
     */
    public function getApcStoreTTL() {
        return $this->_apcStoreTTL;
    }
}