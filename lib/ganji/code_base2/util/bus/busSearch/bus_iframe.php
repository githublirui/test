<?php
    /**
     * 公交iframe
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    require_once(APP_PATH  . '/common/LinkUtil.class.php');
    require_once(APP_PATH  . '/help/includes/HelpHelper.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusAssortKeyDb.class.php');
    
    $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_IFRAME, false);
    $var = array(
        'city'     => HttpHandler::getDomain(),
        'cityName' => array_shift(BusUtils::getCityRelate(HttpHandler::getDomain(), false)),
    );
    $tpl->display($var);
    exit();