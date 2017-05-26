<?php
    /**
     * 周边公交线路
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    //Include Class Files
    require_once(APP_PATH . '/ditu/class/DituRoundBus.class.php');
    
    $line = array();
    $type = (isset($_GET['type']) && strlen(($_GET['type']))) ? $_GET['type'] : false;
    $id   = (isset($_GET['id']) && strlen(($_GET['id'])))     ? $_GET['id']   : false;
    if ($type !== false && $id !== false)
    {
        $query             = $_SERVER['QUERY_STRING'];
        $city              = (isset($_GET['city']) && !empty($_GET['city']))   ? $_GET['city']          : DituConfig::DEFAULT_CITY_NAME;
        $range             = (isset($_GET['range']) && !empty($_GET['range'])) ? intval($_GET['range']) : DituConfig::DEFAULT_RANGE;
        list($lngX, $latY) = DituUtils::getCoordinates();
        $line              = DituRoundBus::getRoundBus($type, $id, $lngX, $latY, $query, $city, $range);
    }
    echo serialize($line);
    exit;