<?php
/**
 * 本地测试工具配置文件
 * @author chenyihong <chenyihong@ganji.com>
 * @version 2013/08/15
 * @copyright ganji.com 2013
 */

class EnvDomain {
    public static $ENVIRONMENT = array(
        'dev' => array(
            'domain' => 'dev.mobds.ganjistatic3.com',
            'title' => 'dev',
            'des' => '本地开发环境',
        ),
        'test1' => array(
            'domain' => 'mobds.ganjistatic3.com',
            'title' => 'test1',
            'des' => 'test1 测试环境',
        ),
        'web6' => array(
            'domain' => 'mobtestweb6.ganji.com',
            'title' => 'web6',
            'des' => 'web6 测试环境',
        ),
        'online' => array(
            'domain' => 'mobds.ganji.cn',
            'title' => 'online',
            'des' => '线上正式环境',
        ),
    );
}
