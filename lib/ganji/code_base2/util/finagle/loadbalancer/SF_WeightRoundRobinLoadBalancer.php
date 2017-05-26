<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/loadbalance/SF_WeightRoundRobinLoadBalancer.php
 * @create        [2014-05-08]赵卫国    简单实现权重轮询方案（需要保存状态）
 * @lastupdate    [2014-05-08]赵卫国
 *
 * @brief 权重轮询调度负载均衡
 *
 * @desc 能实现完全的按weight轮询，每次是哪个结点是提前能确定的（方便测试用例）
 * @缺点  对apc或其他缓存的强依赖，需要保存状态信息
 */



require_once FINAGLE_BASE . '/loadbalancer/SF_LoadBalancer.php';
class SF_WeightRoundRobinLoadBalancer implements SF_LoadBalancer{
    const CURRENT_NUMBER = "currentNum";
    const CURRENT_WEIGHT = "currentWeight";

    const APC_EXPIRE_TIME = 0;
    const START_NUM = 1;

    public function __construct() {
    }

    public function  match($nodes) {

        $len = count($nodes);
        if($len ==1) {
            return current($nodes);
        }
        // 当前服务器权值
        if(apc_exists(self::CURRENT_WEIGHT)) {
            $weight = $this->getApc(self::CURRENT_WEIGHT);
        } else {
            $weight = $this->getMaxWeight($nodes);
            $this->setApc(self::CURRENT_WEIGHT, $weight);
        }
        // 上一次选择的服务器
        if($this->hasApcKey(self::CURRENT_NUMBER)) {
            $num = $this->getApc(self::CURRENT_NUMBER);
        } else {
            $num = self::START_NUM;
        }

        $i = $num;
        while(true) {
            $i = ($i+1) % $len;
            if($i==0) { // 此weight下轮询一遍
                $weight = $weight - $this->getGcd($nodes);  //每次对比权重减少
                if($weight <= 0) {
                    $weight = $this->getMaxWeight($nodes);
                    if($weight == 0) {
                        throw new SF_NodeGetWeightException("Didn't get the right weight");
                        return false;
                    }
                    $this->setApc(self::CURRENT_WEIGHT, $weight);
                }
            }

            $node = $nodes[$i]; //得到当前结点
            if($node->weight >= $weight) {
                if($node->down()) {
                    continue;
                }
                $this->setApc(self::CURRENT_NUMBER, $i);
                return $node;
            }

        }

    }

    // 从apc通过key得到value的值
    public function getApc($apc_key) {
        if(apc_exists($apc_key)) {
            return apc_fetch($apc_key);
        } else {
            return false;
        }
    }

    // 设定apc中指定key对应value的值
    public function setApc($apc_key, $apc_value, $expireTime=self::APC_EXPIRE_TIME) {
        apc_store($apc_key, $apc_value, $expireTime);
    }

    public function hasApcKey($key) {
        return apc_exists($key);
    }



    // 获取最大公约数
    public function getGcd($nodes) {
        return 1;
    }

    // 得到结点中weight最大数
    public function getMaxWeight($nodes) {
        $max = 0;
        foreach($nodes as $node) {
            if($node->down()) {
                continue;
            }
            if($node->weight > $max) {
                $max = $node->weight;
            }
        }
        return $max;
    }
}