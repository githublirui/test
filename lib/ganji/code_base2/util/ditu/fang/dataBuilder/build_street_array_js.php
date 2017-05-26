<?php
    /**
     * 地图找房地标js配置生成
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
    //Include Class Files
    require_once(APP_PATH  . '/ditu/fang/common/DituFindFangConfig.class.php');
    
    foreach (DituFindFangConfig::$VALID_CITY as $city)
    {
    	$element  = 'var district=[';
    	$content  = 'var street=[';
    	$district = GeographyManager::getDistrictOption($city);
    	$count    = count($district) - 1;
    	$i        = 0;
    	foreach ($district as $key => $value)
    	{
    		$obj      = GeographyManager::getDistrictPropertyByDistrictId($city, $key);
    		$element .= "{name:'" . trim($value) . "',url:'{$obj->url}'}";
    		$street   = GeographyManager::getMultitudeStreetPropertyByDistrictId($city, $key, 'housing');
    		$content .= "{";
    		$total    = count($street) - 1;
    		$j        = 0;
    		$flag     = count($street);
    		foreach ($street as $item)
    		{
    			if ($item->streetName == '其他' || (isset(DituFindFangConfig::$INVALID_SHOW[$city][$value]) && array_search($item->streetName, DituFindFangConfig::$INVALID_SHOW[$city][$value]) !== false))
    			{
    				$j++;
    				$flag--;
    				continue;
    			}
    			$content .= "'" . trim($item->streetName) . "':{url:'{$item->url}',id:'{$item->scriptIndex}'}";
    			if ($j < $total)
    			{
    				$content .= ',';
    			}
    			$j++;
    		}
    		if (substr($content, -1) == ',')
    		{
    			$content = substr($content, 0, -1);
    		}
    		$content .= '}';
    		if ($i < $count)
    		{
    			$element .= ',';
    			$content .= ',';
    		}
    		$i++;
    		echo "{$value}:{$flag}<br/>";
    	}
    	$element .= '];';
    	$content .= '];';
    	if (file_exists("../../../media/js/ditu/fang/config/{$city}/") === false && mkdir("../../../media/js/ditu/fang/config/{$city}/", 0777, true) === false)
    	{
    		echo "Fail to mkdir {$city}<br/>";
    		continue;
    	}
    	if (file_exists("../../../media/js/ditu/fang/config/{$city}/street.js"))
    	{
    		$last = system("mv -f ../../../media/js/ditu/fang/config/{$city}/street.js ../../../media/js/ditu/fang/config/{$city}/street.js.bak", $return);
    		echo "SYSTEM MV {$city} LAST={$last} RET={$return}<br/>";
    	}
    	if (($ret = file_put_contents("../../../media/js/ditu/fang/config/{$city}/street.js", $element . $content)) === false)
    	{
    		echo "Fail to build {$city} street.js<br/>";
    	}
    	else
    	{
    		echo "Success to build {$city} street.js<br/>";
    	}
    }