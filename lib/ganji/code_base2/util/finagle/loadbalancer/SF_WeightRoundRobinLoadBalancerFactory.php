<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/loadbalance/SF_WeightRoundRobinLoadBalancerFactory.php
 * @create        [2014-05-08]赵卫国
 * @lastupdate    [2014-05-08]赵卫国
 * @other
 *
 *
 * @brief 把Exception类进行封装
 */


	require_once  FINAGLE_BASE . '/loadbalancer/SF_LoadBalancerFactory.php';
	require_once FINAGLE_BASE . '/loadbalancer/SF_WeightRoundRobinLoadBalancer.php';

	class SF_WeightRoundRobinLoadBalancerFactory implements SF_LoadBalancerFactory {

        public function get() {
            return new SF_WeightRoundRobinLoadBalancer();
        }
    }


