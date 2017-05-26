<?php
    /**
     * 地图找房
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu_Fang
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */

	//Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php'); 
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    //Include Class Files
    require_once(APP_PATH  . '/ditu/fang/common/DituFindFangConfig.class.php');
    require_once(APP_PATH  . '/housing/common/HousingRentShareSellPageConfig.class.php');
    require_once(APP_PATH  . '/housing/common/HousingCommonPageConfig.class.php');
    
    $city = HttpHandler::getDomain();
    if (array_search($city, DituFindFangConfig::$VALID_CITY, true) === false)
    {
    	HttpHandler::displayPageNotFound();
    }
    $type = intval(HttpHandler::getGET(DituFindFangConfig::QUERY_STRING_TYPE));
    if (array_search($type, DituFindFangConfig::$VALID_TYPE, true) === false)
    {
    	HttpHandler::displayPageNotFound();
    }
    if (array_search($city, HousingRentShareSellCommonPageConfig::$DOMAIN_IN_BSGS) !== false)
    {
    	$cityType = DituFindFangConfig::CITY_TYPE_BSGS;
    }
    else if (array_search($city, HousingRentShareSellCommonPageConfig::$DOMAIN_IN_37) !== false)
    {
    	$cityType = DituFindFangConfig::CITY_TYPE_37;
    }
    else
    {
    	$cityType = DituFingFangConfig::CITY_TYPE_OTHER;
    }
    $str = sprintf(DituFindFangConfig::HOUSE_CONFIG_VAR, DituFindFangConfig::$TYPE_STR[$type], $cityType);
    $tpl = new SmartyEngine(SITE_PATH . DituFindFangConfig::TEMPLATE_FILE, false);
    $var = array(
    	DituFindFangConfig::TEMPLATE_VAR_AGENT    => HousingCommonPageConfig::$AGENT_TYPE,
    	DituFindFangConfig::TEMPLATE_VAR_AREA     => HousingCommonPageConfig::$AREA_TYPE_IN_URL,
    	DituFindFangConfig::TEMPLATE_VAR_CITY     => GeographyManager::getCityPropertyByDomain($city),
    	DituFindFangConfig::TEMPLATE_VAR_DEBUG    => DituFindFangConfig::DEBUG,
    	DituFindFangConfig::TEMPLATE_VAR_DISTRICT => GeographyManager::getDistrictOption($city),
    	DituFindFangConfig::TEMPLATE_VAR_FANGXING => HousingCommonPageConfig::$HUXING_SHI_SEARCH,
    	DituFindFangConfig::TEMPLATE_VAR_PRICE    => isset(HousingRentShareSellCommonPageConfig::$$str) ? HousingRentShareSellCommonPageConfig::$$str : array(),
    	DituFindFangConfig::TEMPLATE_VAR_SWITCH   => (count(DituFindFangConfig::$VALID_CITY) > 1),
    	DituFindFangConfig::TEMPLATE_VAR_TYPE     => $type,
    );
	$tpl->display($var);
	exit();