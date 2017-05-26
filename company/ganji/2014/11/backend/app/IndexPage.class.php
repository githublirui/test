<?php

/**
 * 客户端后台管理
 * @author lirui1 <lirui1@ganji.com>
 * @version 2014/10/07
 * @copyright ganji.com
 */
class IndexPage extends BackendPage {

    public function defaultAction() {
        $this->render(array(
                ), 'index.php');
    }

}
