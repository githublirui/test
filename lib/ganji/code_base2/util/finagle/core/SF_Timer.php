<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/core/SF_Timer.php
 * @create        [2014-05-19]赵卫国
 *
 * @other
 *
 * @brief SF框架的时间点记录，主要用于统计stats里耗时时间
 */


class SF_Timer {
    private $start;

    private $timerName;

    public function __construct($timerName) {
        $this->timerName = $timerName;
        $this->start = $this->microtime_float();
    }


    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec) * 1000 * 1000;
    }


    function  __get($name) {
        return $this->$name;
    }

}