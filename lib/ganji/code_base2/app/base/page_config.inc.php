<?php

/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /ganji_v5/app/common/PageBase.class.php
 * @author        longweiguo@ganji.com
 * @date          2011-08-20
 *
 * 页面配置变最
 */

$GLOBALS['PAGE_CONFIG'] = array(
	'content_type'              => 'html',      // 数据类型，参考：PageResponse.class.php
    'jsonp_callback'            => '',          // 使用jsonp返回时的js函数名，只有当content_type=='jsonp'时有用
	'helper_path'               => array(),     // 模板插件所在目录路径，可以是数组
	'tpl_type'                  => 'default',   // 模板引擎类型，参考：TplNamespace.class.php
	'tpl_config'                => array(       // 模板引擎具体配置参数
		'template_path' => '',
		'compile_path'  => '',
		'plugin_path'   => array(),
		'cache_path'    => '',
		'is_cached'     => '',
	),
	'tpl_file'                  => '',          // 模板文件
	'tpl_file_404'              => '',          // 404模板文件
	'app_path'                  => '',          // app所在的路径，结尾不要“/” 
	'query_string_module_name'  => 'mod',       // QUERY_STRING中表示分发到指定模块的下标
	'query_string_action_name'  => 'act',       // QUERY_STRING中表示分发到指定方法的下标
	'module_name_default'       => 'Index',     // 默认要执行的模块
	'module_name_ext'           => 'Page',      // 模块名称的后缀
	'action_name_default'       => 'default',   // 默认要执行的模块中的方法
	'action_name_ext'           => 'Action',    // 方法名称的后缀
);

