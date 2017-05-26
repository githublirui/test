<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/stats/SF_Stats.php
 * @create        [2014-05-21]赵卫国
 *
 * @brief 处理SF框架中的数据收集工作的接口
 */


interface SF_Stats {

    /**
     * @param $statsName
     * @brief 调用次数统计
     */
    public function count($statsName);


    /**
     * @param $statsName
     * @brief: 时间段统计开始
     */
    public function timingStart($statsName);


    /**
     * @param $name
     * @brief 时间段统计结束
     */
    public function timingEnd($statsName);


}


