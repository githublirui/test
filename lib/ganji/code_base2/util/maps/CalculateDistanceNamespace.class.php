<?php
/*
 * 经纬度计算
 *     从客户端引入,CalculateDistance
 *
 * @author wangjun@ganji.com 
 */
class CalculateDistanceNamespace
{
    const EARTHRADIUS = 6378.137; //地球半径
    const PI = 3.141592653;
    public  function GetDistance($lat1,$lng1,$lat2,$lng2)
    {
        $rad_lat1 = self::Rad($lat1);
        $rad_lat2 = self::Rad($lat2);
        $a = $rad_lat1 - $rad_lat2;
        $b = self::Rad($lng1) - self::Rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($rad_lat1) * cos($rad_lat2) * pow(sin($b / 2), 2)));
        $s *= self::EARTHRADIUS;
        return round($s * 100000) / 100.0 / 1000; // 返回单位为公里, 去掉 / 1000 返回单位为米
    }
    public function Rad($number)
    {
        return $number * self::PI / 180.0;
    } 
    public function GetMinMaxLatLng($latitude,$longitude,$radius)
    {    
        $radiusLatLng = array();
        $longitudeVal = $radius * 180 / self::EARTHRADIUS / self::PI;
        $latitudeVal = $radius / 1.852 / 100;
        $maxLng = $longitude + $longitudeVal;
        $minLng = $longitude - $longitudeVal;

        $maxLat = $latitude + $latitudeVal; 
        $minLat = $latitude - $latitudeVal;

        $radiusLatLng['minlat'] = $minLat;
        $radiusLatLng['maxlat'] = $maxLat;
        $radiusLatLng['minlng'] = $minLng;
        $radiusLatLng['maxlng'] = $maxLng; 

        return  $radiusLatLng;
    }
}
