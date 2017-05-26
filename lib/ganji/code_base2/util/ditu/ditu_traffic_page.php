<?php
    /**
     * 交通地图展示页面
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
    require_once(APP_PATH . '/ditu/class/DituTraffic.class.php');
    
    $ditu = new DituTraffic;