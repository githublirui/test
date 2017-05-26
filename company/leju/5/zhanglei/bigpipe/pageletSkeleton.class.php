<?php

class PageletSkeleton extends Pagelet {

    public $skeleton = true;
    public $tpl = 'templates/index.phtml';
    public $name = 'skeleton';

    public function prepareData() {
        return array('text' => 'aaaa');
    }

}
