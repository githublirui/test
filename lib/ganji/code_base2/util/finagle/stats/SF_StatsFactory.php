<?php

interface SF_StatsFactory {


    /**
     * @param int $rate   stats收集比例（0到1）
     * @return SF_Stats对象
     * @brief 得到stats对象
     */
    public static function get($rate=1);
}