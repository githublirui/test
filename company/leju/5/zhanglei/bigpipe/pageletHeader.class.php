<?php

class PageletHeader extends Pagelet {

    public $name = 'header';
    public $tpl = 'templates/header.phtml';

    public function prepareData() {
        return array('demo' => 'header');
    }

}
