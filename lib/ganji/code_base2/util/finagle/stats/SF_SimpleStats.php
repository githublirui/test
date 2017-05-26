<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/stats/SF_SimpleStats.php
 * @create        [2014-05-12]赵卫国
 * @update        [2014-05-21]赵卫国
 *
 * @other
 *
 * @brief 处理SF框架中的数据收集工作，往stats发送数据
 */

require_once CODE_BASE2.'/util/profile/statsd.php';

require_once FINAGLE_BASE . "/core/SF_Timer.php";
require_once FINAGLE_BASE . "/stats/SF_Stats.php";


class SF_SimpleStats implements SF_Stats{
    /**
     * @var 时间记录参数
     */
    private $timers = array();


    /**
     * @param int $rate  采样比例(0-1)
     */
    public function __construct($rate) {
        $this->rate = $rate;
    }

    public function count($statsKey) {
        $statsKey = "finagle.php." . $statsKey;
        StatsD::get()->counting($statsKey, 1, $this->rate);
    }


    public function timingStart($statsKey) {
        $timer = new SF_Timer($statsKey);
        return $timer;
    }

    public function timingEnd($timer) {
        $start = $timer->start;
        $end = $timer->microtime_float();
        $time = $end - $start;

        $statsKey = "finagle.php." . $timer->timerName;
        StatsD::get()->timing($statsKey, $time, $this->rate);

    }


}


