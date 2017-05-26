<?php

/**
 * 
 * 运营模块相关配置
 */
class CategoryMoudleConfig {

    public static $customers = array(
        '705' => array(
            'name' => 'IPhone赶集生活',
            'categoryIds' => array(
                '0' => array('name' => '首页'),
                '5' => array(
                    'name' => '本地生活服务',
                    'virtualIds' => array('1' => '生活家政', '2' => '本地服务'),
                ),
            ),
        ),
        '801' => array(
            'name' => 'Android赶集生活',
            'categoryIds' => array(
                '0' => array(
                    'name' => '首页',
                ),
                '5' => array(
                    'name' => '本地生活服务',
                    'virtualIds' => array('1' => '生活家政', '2' => '本地服务'),
                ),
            ),
        ),
    );

    /**
     * 首页跳转类型
     * @var type 
     */
    public static $itemJumpTypes = array(
        1 => '大类首页', 2 => '帖子列表页',
        3 => '大类下的小类配置页面（中间页）', 4 => '内置wap页面',
        5 => '外部浏览器wap页面', 6 => '应用程序点击后直接下载',
        7 => '生活号码簿（驴小二）', 8 => '百宝箱', 9 => '婚恋交友',
        10 => '手机安全鉴定', 11 => '团购', 12 => '游戏中心',
        13 => '电影', 14 => '房贷计算器', 15 => '居家必备', 16 => '充话费',
        17 => '简历库', 18 => '装修帮', 19 => '百度贴吧', 20 => '通用h5页面',
    );

    /**
     * item dataSource类型
     * @var type 
     */
    public static $itemDataSources = array(
        '1' => '配置数据',
        '2' => '接口数据',
        '3' => '跳wap页',
        '4' => '跳装修首页',
        '5' => '跳native',
        '6' => '跳服务频道-招标发布',
        '7' => '跳本地懒人找房',
        '8' => '跳本地购房宝典',
        '9' => '跳本地房贷计算器',
    );

    /**
     * 显示方式
     * @var type 
     */
    public static $showModes = array(
        '1' => '图片',
        '2' => '图片+文字',
        '3' => '纯文字',
    );

}

?>
