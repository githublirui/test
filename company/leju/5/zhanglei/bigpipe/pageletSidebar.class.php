<?php

class PageletSidebar extends Pagelet {

    public $name = 'testimonial';
    public $tpl = 'templates/sidebar.phtml';

    public function prepareData() {
//        sleep(5);
        return array(
            'sidebars' => array(
                '苗神威武雄壮1',
                '苗神威武雄壮2',
                '苗神威武雄壮3',
                '苗神威武雄壮4',
                '苗神威武雄壮5',
                '苗神威武雄壮6',
                '苗神威武雄壮7',
                '苗神威武雄壮8',
            )
        );
    }

}
