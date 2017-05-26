<?php

require_once FINAGLE_BASE . "/stats/SF_StatsFactory.php";
require_once FINAGLE_BASE . "/stats/SF_NotStats.php";

class SF_NotStats_Factory implements SF_StatsFactory {


    public static function get($rate=1) {
        if($rate>1 || $rate <0) {
            $rate = 1;
        }
        return new SF_NotStats($rate);
    }


}