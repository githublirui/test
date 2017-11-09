<?php

/**
 * 分页类
 * @author Administrator
 */
class Pager {

    public static function doPager($lists, $chuck, $callBack) {
        $chunckList = array_chunk($lists, $chuck);
        return $callBack['class']->$callBack['method']($chunckList);
    }

}

class Test {

    public function run() {
        $classIds = range(1, 2000);
        $callBack = array(
            'class' => $this,
            'method' => 'doClass',
        );
        Pager::doPager($classIds, 200, $callBack);
    }

    public function doClass($chunckList) {
         $stuIds = range(1, 2000);
        Pager::doPager($classIds, 200, $callBack);
    }

}

$test = new Test();

$test->run();
?>
