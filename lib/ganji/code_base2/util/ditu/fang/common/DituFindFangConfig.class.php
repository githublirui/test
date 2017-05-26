<?php
    /**
     * 地图找房配置
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu_Fang
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */

	class DituFindFangConfig
	{
		const CITY_TYPE_BSGS        = 'BSGS';
		const CITY_TYPE_37          = '37';
		const CITY_TYPE_OTHER       = 'OTHER';
		const DEBUG                 = false;
		const HOUSE_CONFIG_VAR      = 'PRICE_%s_TYPE_IN_URL_%s';
		const QUERY_STRING_TYPE     = 'type';
		const TEMPLATE_FILE         = '/templates/map/fang/main.htm';
		const TEMPLATE_VAR_AGENT    = 'agent';
		const TEMPLATE_VAR_AREA     = 'area';
		const TEMPLATE_VAR_CITY     = 'city';
		const TEMPLATE_VAR_DEBUG    = 'debug';
		const TEMPLATE_VAR_DISTRICT = 'district';
		const TEMPLATE_VAR_FANGXING = 'fang_xing';
		const TEMPLATE_VAR_PRICE    = 'price';
		const TEMPLATE_VAR_SWITCH   = 'switch';
		const TEMPLATE_VAR_TYPE     = 'type';
		const VALID_TYPE_FANG1      = 1;
		const VALID_TYPE_FANG3      = 3;
		const VALID_TYPE_FANG5      = 5;
		public static $VALID_CITY   = array(
			'bj',
		);
		public static $INVALID_SHOW = array(
            'bj' => array(
                '昌平' => array(
					'城北','城南'
				),
				'平谷' => array(
					'渔阳',
				),
				'怀柔' => array(
					'泉河',
				),
            ),
		);
		public static $TYPE_STR     = array(
			self::VALID_TYPE_FANG1 => 'RENT',
			self::VALID_TYPE_FANG3 => 'SHARE',
			self::VALID_TYPE_FANG5 => 'SELL',
		);
		public static $VALID_TYPE   = array(
			self::VALID_TYPE_FANG1,
			self::VALID_TYPE_FANG3,
			self::VALID_TYPE_FANG5,
		);
	}