<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/stats/SF_NotStats.php
 * @create        [2014-05-21]赵卫国
 * @update        [2014-05-12]赵卫国
 *
 * @other
 *
 * @brief 如用户不收集，默认使用此类，此类不对stats进行收集，不做任何操作
 */

require_once CODE_BASE2.'/util/profile/statsd.php';

require_once FINAGLE_BASE . "/stats/SF_Stats.php";


class SF_NotStats implements SF_Stats{

    /**
     * @param int $rate  采样比例(0-1)
     */
    public function __construct($rate) {
        $this->rate = $rate;
    }

    public function count($statsName) {
        // do noting
    }

    public function timingStart($statsName){
        // do nothing
    }

    public function timingEnd($statsName){
        // do nothing
    }



}


