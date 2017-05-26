<?php

require_once FINAGLE_BASE . "/stats/SF_StatsFactory.php";
require_once FINAGLE_BASE . "/stats/SF_SimpleStats.php";

class SF_SimpleStats_Factory implements SF_StatsFactory {


    public static function get($rate=1) {
        if($rate>1 || $rate <0) {
            $rate = 1;
        }
        return new SF_SimpleStats($rate);
    }


}