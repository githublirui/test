<?php
class Mercator
{
    //{{{ static 
    private static $LL2MC = array(
        array(-0.00157021024440, 1.113207020616939e+005, 1.704480524535203e+015, -1.033898737604234e+016, 2.611266785660388e+016, -3.514966917665370e+016, 2.659570071840392e+016, -1.072501245418824e+016, 1.800819912950474e+015, 82.5),
        array(8.277824516172526e-004, 1.113207020463578e+005, 6.477955746671608e+008, -4.082003173641316e+009, 1.077490566351142e+010, -1.517187553151559e+010, 1.205306533862167e+010, -5.124939663577472e+009, 9.133119359512032e+008, 67.5),
        array(0.00337398766765, 1.113207020202162e+005, 4.481351045890365e+006, -2.339375119931662e+007, 7.968221547186455e+007, -1.159649932797253e+008, 9.723671115602145e+007, -4.366194633752821e+007, 8.477230501135234e+006, 52.5),
        array(0.00220636496208, 1.113207020209128e+005, 5.175186112841131e+004, 3.796837749470245e+006, 9.920137397791013e+005, -1.221952217112870e+006, 1.340652697009075e+006, -6.209436990984312e+005, 1.444169293806241e+005, 37.5),
        array(-3.441963504368392e-004, 1.113207020576856e+005, 2.782353980772752e+002, 2.485758690035394e+006, 6.070750963243378e+003, 5.482118345352118e+004, 9.540606633304236e+003, -2.710553267466450e+003, 1.405483844121726e+003, 22.5),
        array(-3.218135878613132e-004, 1.113207020701615e+005, 0.00369383431289, 8.237256402795718e+005, 0.46104986909093, 2.351343141331292e+003, 1.58060784298199, 8.77738589078284, 0.37238884252424, 7.45),
    );                                                           
    private static $LLBAND = array(75, 60, 45, 30, 15, 0);
    private static $mapCenter = array(
        'bj' => array(116.46, 39.92),
    );
    private static $mapSize = array('width'=>270, 'height'=>270);
    //}}}
    public function getCityMapCenter($domain){
        return self::$mapCenter[$domain];
    }
    /* {{{convertLL2MC*/
    /**
     * @brief 
     *
     * @param $lnglat
     *
     * @returns   
     */
    public static function convertLL2MC($lnglat){
        $point = array('lng'=>$lnglat[0], 'lat'=>$lnglat[1]);
        $point['lng'] = ($point['lng']>180) ? ($point['lng']-360) : ($point['lng']+360);
        $point['lat'] = min($point['lat'], 74);
        $temp = $point;
        $factor = null;
        for ($i = 0; $i < count(self::$LLBAND); ++$i) {
            if ($temp['lat'] >= self::$LLBAND[$i]) {
                $factor = self::$LL2MC[$i];
                break;
            }
        }
        if (null != $factor) {
            for ($i = count(self::$LLBAND) - 1; $i >= 0; --$i) {
                if ($temp['lat'] <= -self::$LLBAND[$i]) {
                    $factor = self::$LL2MC[$i];
                    break;
                }
            }
        }
        $mc = self::convertor($point, $factor);
        if (empty($mc)) {
            return array();
        }
        return array('lng'=>round($mc['lng'], 2), 'lat'=>round($mc['lat'], 2));
    }//}}}
    /* {{{convertor*/
    /**
     * @brief 
     *
     * @param $fromPoint
     * @param $factor
     *
     * @returns   
     */
    public static function convertor($fromPoint, $factor){
        if (empty($fromPoint) || empty($factor)) {
            return;
        }
        $x = $factor[0] + $factor[1]*abs($fromPoint['lng']);
        $temp = abs($fromPoint['lat']) / $factor[9];
        $y = $factor[2] +
            $factor[3] * $temp +
            $factor[4] * $temp * $temp +
            $factor[5] * $temp * $temp * $temp +
            $factor[6] * $temp * $temp * $temp * $temp +
            $factor[7] * $temp * $temp * $temp * $temp * $temp +
            $factor[8] * $temp * $temp * $temp * $temp * $temp * $temp;
        $x *= ($fromPoint['lng'] < 0 ? -1 : 1);
        $y *= ($fromPoint['lat'] < 0 ? -1 : 1);
        return array('lng'=>$x, 'lat'=>$y);
    }//}}}
    /* {{{pointToPixel*/
    /**
     * @brief 
     *
     * @param $point
     * @param $zoom
     * @param $mapCenter (135,135) 
     * @param $mapSize (270,270)
     *
     * @returns   
     */
    public static function pointToPixel($point, $zoom, $mapCenter){
        if (!$point) {
            return;
        }
        $point = self::lngLatToMercator($point);
        $zoomUnits = self::getZoomUnits($zoom);
        $x = round(($point['lng'] - $mapCenter['lng']) / $zoomUnits + self::$mapSize['width'] / 2);
        $y = round(($mapCenter['lat'] - $point['lat']) / $zoomUnits + self::$mapSize['height'] / 2);
        return array($x, $y);
    }//}}}
    /* {{{lngLatToMercator*/
    /**
     * @brief 
     *
     * @param $point
     *
     * @returns   
     */
    public static function lngLatToMercator($point) {
        return self::convertLL2MC($point);
    }//}}}
    /* {{{getZoomUnits*/
    /**
     * @brief 
     *
     * @param $zoom
     *
     * @returns   
     */
    public static function getZoomUnits($zoom) {
        return pow(2, (18 - $zoom));
    }//}}}
}
