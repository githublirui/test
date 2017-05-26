<?php

/**
 * 
 * 
 */
class BaseOperate {

    public function __construct($param) {
        $this->param = $param;
    }

    public function operate() {
        $funcName = 'refresh';
        return call_user_func(array(get_called_class(), $funcName));
    }

    protected function refresh() {
        return 'base operate' . var_export($this->param, true);
    }

}

