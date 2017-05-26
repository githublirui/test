<?php

/**
 * 
 * 
 */
require_once dirname(__FILE__) . '/BaseOperate.class.php';

class NormalOperate extends BaseOperate {

    public function refresh() {
        return 'Normal Refresh' . var_export($this->param, true);
    }

}
