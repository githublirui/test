<?php
    /**
     * 公交通用工具类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    final class BusUtils
    {
        /**
         * Decode the Coordinate of the MapABC
         *
         * @param  array|string $inputLngLats
         * @return array
         */
        public static function decodeMapABCCoordinate($inputLngLats = array())
        {
            $__keys__ = array(
                array(0, 2, 1, 2, 8, 9, 4, 1, 7, 2, 5, 3, 9),
                array(0, 3, 2, 2, 9, 5, 8, 2, 6, 8, 4, 6, 3),
                array(1, 5, 2, 7, 1, 4, 7, 2, 4, 1, 4, 3, 0),
                array(0, 7, 8, 3, 4, 9, 0, 6, 7, 7, 4, 4, 2),
                array(0, 2, 1, 8, 4, 9, 3, 2, 3, 1, 5, 7, 8),
                array(0, 0, 9, 5, 4, 7, 3, 0, 8, 7, 5, 2, 8),
                array(0, 1, 5, 1, 1, 8, 2, 7, 1, 9, 1, 3, 5),
                array(0, 5, 2, 5, 6, 0, 3, 4, 6, 7, 1, 3, 5),
                array(1, 3, 2, 1, 8, 1, 8, 3, 7, 9, 2, 7, 0),
                array(1, 2, 7, 7, 4, 3, 1, 5, 5, 0, 6, 4, 4),
                array(1, 5, 2, 8, 9, 2, 5, 9, 6, 7, 3, 3, 5),
                array(1, 7, 9, 4, 5, 0, 9, 4, 9, 6, 1, 9, 9),
                array(0, 6, 8, 3, 3, 6, 3, 5, 2, 0, 0, 9, 1),
                array(1, 1, 1, 4, 7, 8, 6, 9, 6, 8, 8, 4, 6),
                array(0, 5, 2, 1, 2, 5, 7, 0, 0, 4, 7, 4, 1),
                array(0, 7, 6, 4, 2, 3, 9, 0, 7, 8, 5, 6, 7),
                array(0, 1, 7, 6, 0, 5, 4, 7, 6, 7, 7, 5, 7),
                array(0, 5, 2, 9, 8, 1, 7, 8, 3, 8, 5, 4, 5),
                array(0, 4, 3, 1, 2, 8, 3, 7, 0, 9, 4, 8, 8),
                array(1, 0, 6, 7, 9, 4, 3, 5, 2, 9, 8, 7, 7),
                array(1, 6, 4, 4, 6, 7, 1, 4, 4, 2, 6, 7, 5),
                array(0, 8, 1, 7, 7, 5, 2, 6, 4, 3, 9, 7, 5),
                array(1, 7, 0, 5, 6, 2, 5, 2, 7, 4, 6, 2, 8),
                array(0, 4, 9, 2, 3, 0, 5, 4, 7, 8, 7, 0, 5),
                array(1, 1, 0, 5, 1, 7, 2, 8, 7, 2, 6, 9, 3),
                array(1, 4, 2, 3, 6, 1, 5, 3, 2, 0, 3, 6, 2),
                array(1, 1, 6, 5, 1, 0, 6, 8, 9, 7, 1, 7, 9),
                array(0, 6, 5, 4, 0, 7, 1, 7, 6, 2, 5, 4, 2),
                array(1, 9, 8, 6, 6, 6, 8, 4, 5, 4, 0, 4, 0),
                array(1, 2, 7, 1, 5, 0, 6, 8, 0, 1, 3, 7, 9),
                array(1, 1, 6, 4, 9, 8, 6, 0, 6, 2, 1, 9, 8),
                array(0, 0, 1, 9, 5, 3, 3, 9, 6, 7, 4, 1, 1),
                array(0, 2, 8, 5, 7, 8, 6, 7, 3, 3, 1, 6, 4),
                array(1, 8, 2, 5, 8, 4, 7, 6, 8, 8, 5, 7, 6),
                array(0, 8, 3, 4, 9, 6, 1, 7, 8, 3, 0, 5, 5),
                array(1, 3, 2, 6, 7, 4, 2, 8, 7, 4, 9, 6, 8),
                array(1, 8, 8, 9, 3, 9, 1, 8, 5, 7, 2, 5, 0),
                array(0, 5, 8, 3, 1, 8, 8, 0, 3, 9, 3, 8, 1),
                array(1, 6, 0, 1, 1, 0, 3, 4, 3, 3, 3, 5, 9),
                array(1, 0, 5, 1, 7, 9, 6, 2, 4, 6, 0, 3, 5),
                array(1, 8, 2, 0, 9, 7, 1, 0, 5, 5, 8, 0, 6),
                array(1, 8, 9, 6, 7, 3, 9, 4, 1, 9, 6, 6, 2),
                array(0, 6, 0, 0, 8, 2, 6, 5, 9, 4, 1, 6, 2),
                array(1, 7, 9, 7, 9, 4, 4, 2, 1, 1, 5, 7, 4),
                array(1, 3, 0, 4, 3, 4, 6, 8, 6, 9, 1, 7, 0),
                array(0, 1, 2, 3, 9, 4, 1, 8, 7, 2, 2, 9, 8),
                array(1, 6, 5, 3, 2, 7, 6, 6, 9, 0, 0, 7, 7),
                array(1, 6, 8, 4, 9, 7, 8, 0, 3, 6, 5, 4, 8),
                array(0, 6, 6, 0, 9, 9, 4, 5, 5, 6, 8, 3, 7),
                array(1, 0, 1, 3, 4, 0, 0, 1, 4, 8, 5, 7, 0),
                array(1, 0, 2, 5, 8, 2, 2, 4, 8, 9, 7, 1, 6),
                array(1, 4, 2, 6, 6, 8, 4, 5, 6, 6, 4, 5, 9),
                array(1, 4, 4, 1, 7, 2, 0, 4, 6, 3, 3, 6, 7),
                array(0, 2, 2, 3, 8, 0, 0, 8, 6, 0, 2, 1, 7),
                array(0, 9, 4, 4, 8, 1, 2, 7, 3, 2, 6, 8, 0),
                array(0, 9, 8, 4, 2, 1, 4, 5, 2, 4, 9, 5, 1),
                array(0, 7, 2, 4, 7, 4, 3, 2, 4, 1, 5, 6, 9),
                array(1, 1, 8, 4, 8, 8, 8, 4, 3, 4, 1, 2, 5),
                array(0, 3, 2, 7, 5, 7, 0, 2, 7, 4, 5, 3, 5),
                array(0, 3, 0, 4, 6, 6, 6, 5, 7, 2, 1, 9, 5),
                array(1, 5, 6, 0, 1, 3, 2, 7, 3, 0, 9, 8, 6),
                array(0, 5, 5, 1, 7, 1, 0, 7, 9, 0, 3, 5, 7),
                array(0, 5, 4, 9, 7, 9, 7, 3, 8, 0, 1, 6, 3),
                array(1, 9, 2, 7, 3, 7, 9, 4, 3, 9, 8, 8, 2),
                array(0, 3, 1, 8, 9, 0, 9, 0, 4, 5, 5, 0, 9),
                array(1, 8, 6, 1, 7, 7, 2, 4, 7, 9, 2, 0, 8),
                array(0, 6, 1, 2, 7, 1, 4, 8, 4, 1, 1, 6, 0),
                array(0, 3, 9, 8, 5, 5, 3, 0, 8, 7, 9, 3, 5),
                array(0, 8, 4, 3, 7, 3, 1, 8, 2, 9, 1, 4, 7),
                array(0, 1, 5, 3, 4, 0, 5, 5, 5, 8, 0, 7, 2),
                array(0, 1, 7, 1, 8, 2, 1, 9, 8, 6, 1, 7, 0),
                array(0, 7, 1, 6, 9, 7, 2, 7, 2, 4, 4, 3, 6),
                array(0, 6, 2, 7, 2, 3, 4, 9, 3, 0, 1, 6, 3),
                array(0, 2, 9, 1, 9, 9, 9, 1, 9, 5, 4, 4, 4),
                array(0, 1, 8, 7, 0, 0, 5, 2, 1, 5, 7, 4, 6),
                array(1, 9, 0, 8, 7, 3, 3, 5, 5, 4, 9, 0, 1),
                array(1, 5, 8, 0, 1, 7, 0, 2, 3, 7, 3, 2, 9),
                array(1, 3, 2, 0, 5, 2, 7, 5, 0, 2, 6, 8, 1),
                array(0, 2, 7, 2, 3, 2, 2, 9, 6, 9, 4, 1, 6),
                array(1, 6, 4, 7, 9, 6, 5, 9, 5, 8, 2, 7, 1),
                array(1, 8, 1, 2, 6, 0, 2, 4, 0, 8, 0, 1, 6),
                array(1, 6, 2, 4, 1, 2, 4, 1, 7, 2, 7, 0, 6),
                array(0, 1, 8, 0, 5, 0, 4, 5, 5, 1, 0, 4, 7),
                array(0, 8, 7, 6, 4, 3, 5, 5, 7, 8, 4, 9, 0),
                array(0, 2, 7, 7, 0, 1, 6, 6, 1, 0, 9, 3, 5),
                array(0, 7, 6, 9, 8, 3, 8, 6, 2, 9, 3, 7, 0),
                array(1, 6, 6, 6, 0, 3, 0, 1, 0, 2, 5, 6, 1),
                array(0, 0, 4, 5, 1, 0, 9, 4, 4, 9, 4, 0, 9),
                array(0, 1, 6, 9, 4, 7, 5, 7, 8, 3, 5, 7, 0),
                array(1, 2, 7, 1, 6, 6, 1, 5, 2, 8, 6, 3, 8),
                array(1, 9, 1, 6, 7, 5, 1, 7, 4, 7, 6, 1, 8),
                array(1, 7, 6, 7, 0, 2, 9, 6, 9, 8, 6, 7, 8),
                array(0, 9, 8, 7, 3, 8, 1, 5, 2, 5, 2, 7, 5),
                array(0, 7, 3, 5, 7, 9, 7, 6, 6, 9, 1, 7, 5),
                array(1, 6, 7, 3, 4, 4, 7, 6, 2, 6, 6, 2, 3),
                array(0, 1, 4, 2, 2, 8, 5, 0, 9, 2, 7, 3, 1),
                array(0, 1, 4, 2, 1, 0, 0, 2, 1, 8, 9, 8, 3),
                array(1, 7, 0, 8, 7, 9, 9, 6, 4, 8, 6, 2, 2),
                array(1, 9, 3, 9, 9, 8, 7, 0, 8, 1, 1, 7, 3),
                array(1, 0, 4, 3, 5, 8, 0, 4, 6, 5, 4, 5, 8),
                array(0, 4, 8, 0, 5, 2, 3, 2, 3, 9, 4, 2, 3),
                array(0, 7, 9, 0, 9, 7, 2, 7, 7, 0, 4, 8, 5),
                array(1, 6, 5, 5, 3, 3, 2, 6, 1, 3, 4, 7, 1),
                array(0, 2, 9, 0, 0, 2, 9, 1, 8, 8, 2, 8, 4),
                array(1, 3, 2, 5, 0, 6, 2, 5, 3, 3, 6, 1, 1),
                array(1, 9, 2, 9, 3, 3, 8, 9, 9, 7, 2, 3, 7),
                array(1, 1, 8, 4, 0, 8, 2, 4, 8, 0, 0, 9, 2),
                array(1, 5, 2, 6, 0, 6, 1, 3, 0, 4, 7, 3, 8),
                array(1, 9, 3, 8, 1, 1, 7, 8, 6, 9, 0, 6, 8),
                array(1, 3, 2, 7, 7, 2, 2, 4, 2, 5, 8, 3, 0),
                array(1, 1, 1, 0, 7, 7, 3, 4, 7, 3, 6, 6, 8),
                array(0, 9, 4, 2, 8, 9, 4, 8, 4, 3, 2, 5, 3),
                array(0, 1, 0, 9, 2, 7, 2, 3, 9, 4, 5, 0, 8),
                array(1, 0, 4, 5, 8, 4, 0, 0, 5, 2, 2, 1, 2),
                array(0, 5, 0, 4, 5, 3, 2, 5, 4, 1, 3, 6, 9),
                array(1, 3, 0, 2, 7, 8, 1, 7, 7, 3, 5, 5, 9),
                array(1, 3, 7, 0, 0, 5, 8, 1, 7, 5, 6, 5, 2),
                array(1, 8, 1, 9, 9, 9, 4, 8, 6, 0, 7, 7, 3),
                array(0, 8, 3, 6, 2, 7, 4, 2, 1, 9, 1, 6, 8),
                array(0, 4, 4, 4, 2, 6, 0, 4, 0, 1, 5, 1, 7),
                array(1, 2, 7, 4, 7, 6, 6, 6, 3, 7, 7, 2, 9),
                array(0, 9, 8, 9, 3, 3, 3, 9, 0, 7, 4, 2, 3),
                array(0, 7, 6, 0, 9, 1, 7, 2, 4, 5, 8, 3, 3),
                array(1, 6, 1, 5, 5, 3, 1, 3, 2, 1, 0, 5, 6),
                array(0, 6, 2, 4, 1, 6, 6, 3, 4, 9, 2, 7, 0),
                array(1, 6, 3, 2, 3, 6, 1, 7, 7, 5, 6, 7, 1),
                array(1, 0, 4, 9, 2, 3, 3, 6, 2, 6, 9, 3, 2),
                array(0, 3, 7, 3, 9, 1, 3, 9, 5, 8, 5, 8, 9),
                array(1, 9, 0, 0, 3, 0, 9, 1, 2, 7, 8, 0, 3),
                array(1, 0, 1, 2, 7, 7, 0, 0, 1, 8, 4, 1, 1),
                array(0, 0, 5, 5, 9, 6, 9, 8, 1, 2, 1, 7, 2),
                array(0, 1, 8, 7, 9, 0, 3, 5, 6, 3, 2, 9, 4),
                array(1, 3, 1, 5, 7, 5, 0, 8, 5, 3, 2, 5, 0),
                array(1, 1, 7, 3, 5, 0, 7, 7, 9, 6, 8, 9, 0),
                array(0, 7, 7, 0, 9, 4, 2, 8, 8, 0, 2, 2, 0),
                array(1, 6, 5, 8, 3, 1, 0, 9, 0, 2, 7, 2, 9),
                array(1, 3, 5, 8, 4, 7, 6, 3, 1, 4, 3, 4, 7),
                array(0, 8, 8, 7, 8, 2, 7, 0, 3, 9, 6, 2, 9),
                array(1, 1, 6, 2, 6, 7, 5, 2, 5, 0, 8, 5, 5),
                array(0, 9, 6, 7, 3, 0, 2, 3, 9, 5, 3, 7, 4),
                array(1, 5, 2, 7, 3, 6, 0, 8, 3, 3, 9, 0, 3),
                array(0, 3, 6, 8, 9, 1, 7, 7, 3, 8, 7, 3, 8),
                array(0, 1, 2, 5, 4, 9, 8, 0, 3, 6, 4, 0, 4),
                array(1, 2, 4, 1, 6, 8, 1, 5, 8, 3, 6, 4, 3),
                array(1, 9, 3, 1, 0, 8, 4, 4, 0, 1, 6, 0, 8),
                array(0, 4, 5, 1, 0, 2, 1, 7, 1, 6, 1, 3, 3),
                array(0, 9, 5, 6, 8, 2, 2, 4, 0, 3, 9, 8, 1),
                array(1, 9, 3, 5, 4, 3, 1, 2, 2, 2, 0, 8, 7),
                array(0, 5, 6, 8, 1, 5, 7, 7, 8, 9, 4, 0, 6),
                array(1, 0, 4, 6, 4, 6, 7, 4, 6, 0, 3, 6, 2),
                array(1, 3, 3, 0, 2, 5, 3, 1, 9, 2, 3, 6, 8),
                array(0, 6, 9, 6, 3, 6, 9, 6, 2, 1, 5, 0, 7),
                array(1, 6, 5, 3, 0, 0, 0, 6, 2, 3, 8, 6, 0),
                array(1, 0, 7, 1, 2, 0, 3, 0, 3, 0, 8, 8, 0),
                array(0, 7, 1, 4, 3, 1, 8, 6, 7, 8, 1, 5, 4),
                array(0, 6, 3, 5, 5, 4, 8, 9, 4, 8, 3, 1, 7),
                array(0, 6, 4, 3, 1, 0, 7, 2, 9, 0, 5, 6, 7),
                array(0, 6, 3, 7, 7, 0, 6, 8, 6, 7, 4, 6, 0),
                array(0, 4, 2, 7, 2, 4, 1, 4, 6, 1, 8, 1, 7),
                array(1, 1, 7, 9, 0, 7, 0, 5, 1, 8, 6, 3, 5),
                array(1, 2, 0, 2, 7, 2, 7, 9, 1, 2, 7, 0, 3),
                array(0, 3, 3, 6, 2, 0, 9, 1, 1, 0, 3, 5, 8),
                array(1, 4, 0, 9, 9, 2, 5, 6, 5, 6, 8, 0, 5),
                array(0, 3, 5, 3, 3, 3, 4, 6, 7, 5, 7, 0, 5),
                array(0, 5, 8, 8, 5, 8, 5, 4, 7, 0, 5, 7, 3),
                array(0, 5, 0, 7, 6, 4, 2, 7, 8, 3, 6, 1, 4),
                array(0, 4, 7, 8, 6, 5, 3, 7, 7, 5, 7, 0, 7),
                array(1, 3, 6, 5, 3, 0, 8, 5, 4, 9, 7, 7, 1),
                array(1, 4, 8, 2, 8, 2, 8, 3, 4, 9, 4, 6, 7),
                array(1, 4, 1, 6, 9, 4, 5, 7, 7, 4, 6, 7, 7),
                array(0, 2, 8, 2, 3, 0, 7, 7, 1, 0, 1, 1, 0),
                array(1, 2, 2, 4, 5, 4, 7, 1, 0, 1, 8, 6, 7),
                array(0, 0, 7, 2, 4, 7, 2, 8, 2, 4, 4, 3, 9),
                array(1, 9, 1, 3, 2, 4, 1, 3, 3, 7, 5, 6, 1),
                array(1, 4, 7, 4, 6, 8, 6, 7, 4, 4, 1, 2, 8),
                array(0, 1, 6, 7, 3, 9, 0, 4, 7, 2, 9, 6, 7),
                array(0, 1, 3, 9, 1, 1, 1, 1, 6, 3, 0, 1, 1),
                array(1, 2, 7, 0, 2, 0, 7, 9, 7, 2, 1, 5, 2),
                array(0, 9, 1, 0, 4, 2, 8, 2, 2, 4, 2, 4, 0),
                array(1, 1, 7, 9, 7, 9, 3, 0, 5, 3, 4, 5, 2),
                array(0, 0, 7, 4, 3, 0, 8, 6, 7, 7, 7, 9, 6),
                array(0, 7, 0, 4, 0, 6, 7, 6, 3, 2, 0, 7, 1),
                array(0, 4, 8, 8, 0, 5, 3, 0, 7, 8, 4, 7, 9),
                array(0, 6, 3, 3, 3, 6, 6, 3, 7, 0, 4, 8, 3),
                array(0, 1, 2, 0, 6, 0, 3, 1, 0, 9, 9, 8, 0),
                array(0, 7, 0, 3, 8, 2, 5, 0, 7, 5, 0, 0, 4),
                array(1, 8, 8, 8, 2, 0, 6, 2, 5, 6, 2, 3, 2),
                array(1, 6, 2, 5, 8, 0, 1, 9, 7, 3, 7, 6, 0),
                array(0, 3, 6, 1, 9, 1, 6, 8, 2, 6, 5, 2, 5),
                array(0, 3, 9, 7, 8, 9, 4, 5, 4, 8, 5, 5, 1),
                array(1, 1, 5, 5, 2, 5, 3, 4, 5, 3, 5, 0, 9),
                array(1, 0, 9, 4, 9, 6, 1, 7, 0, 0, 6, 0, 1),
                array(0, 8, 4, 9, 9, 9, 3, 4, 1, 3, 5, 7, 7),
                array(0, 7, 8, 0, 0, 3, 5, 5, 9, 4, 1, 8, 1),
                array(1, 7, 3, 7, 6, 3, 2, 5, 6, 2, 7, 5, 0),
                array(0, 0, 2, 6, 0, 6, 6, 2, 7, 6, 1, 6, 2),
                array(1, 1, 6, 4, 7, 7, 9, 7, 0, 6, 2, 6, 6),
                array(0, 2, 1, 1, 4, 7, 6, 8, 8, 8, 9, 4, 3),
                array(0, 0, 8, 7, 5, 1, 9, 3, 1, 9, 8, 6, 0),
                array(0, 3, 4, 4, 0, 7, 1, 8, 7, 2, 7, 9, 9),
                array(1, 0, 4, 5, 3, 6, 0, 6, 6, 6, 4, 1, 5),
                array(0, 9, 7, 9, 9, 5, 9, 2, 3, 0, 4, 6, 2),
                array(1, 6, 5, 2, 7, 2, 1, 3, 5, 2, 5, 2, 1),
                array(1, 9, 9, 4, 8, 6, 3, 7, 8, 3, 3, 0, 6),
                array(0, 8, 2, 6, 6, 7, 8, 2, 1, 3, 2, 9, 2),
                array(0, 4, 8, 1, 9, 2, 4, 8, 4, 5, 4, 6, 4),
                array(1, 1, 7, 0, 7, 3, 5, 1, 4, 9, 5, 3, 1),
                array(1, 7, 8, 8, 3, 5, 3, 1, 5, 7, 6, 1, 9),
                array(1, 4, 5, 6, 5, 3, 2, 5, 3, 0, 3, 5, 5),
                array(0, 0, 2, 1, 3, 8, 9, 1, 0, 9, 7, 6, 7),
                array(0, 0, 7, 6, 1, 9, 1, 9, 5, 8, 9, 4, 0),
                array(1, 5, 4, 4, 6, 8, 7, 3, 9, 9, 0, 7, 4),
                array(1, 3, 0, 4, 8, 1, 2, 3, 9, 7, 1, 9, 5),
                array(1, 2, 6, 1, 4, 6, 9, 4, 7, 1, 1, 2, 6),
                array(0, 1, 6, 7, 5, 8, 3, 2, 7, 0, 4, 1, 1),
                array(1, 6, 2, 7, 8, 7, 6, 8, 7, 2, 0, 3, 3),
                array(0, 2, 1, 9, 2, 6, 7, 5, 9, 5, 2, 2, 2),
                array(0, 5, 2, 0, 4, 7, 7, 3, 8, 1, 5, 0, 9),
                array(1, 6, 5, 8, 6, 4, 0, 9, 6, 9, 0, 1, 8),
                array(1, 2, 0, 8, 7, 9, 2, 4, 4, 0, 9, 8, 9),
                array(1, 6, 5, 2, 0, 6, 1, 0, 4, 4, 1, 5, 8),
                array(1, 5, 4, 2, 5, 6, 2, 5, 6, 2, 2, 9, 5),
                array(1, 6, 9, 7, 2, 5, 1, 0, 6, 9, 1, 8, 1),
                array(0, 0, 3, 9, 9, 0, 6, 7, 9, 5, 7, 4, 6),
                array(1, 5, 8, 9, 9, 0, 6, 7, 9, 7, 9, 6, 1),
                array(1, 3, 6, 4, 6, 3, 6, 8, 4, 5, 2, 8, 3),
                array(0, 7, 4, 8, 4, 9, 7, 8, 0, 0, 1, 2, 2),
                array(0, 4, 2, 9, 1, 3, 8, 8, 3, 0, 0, 9, 8),
                array(1, 9, 0, 9, 2, 1, 2, 9, 3, 6, 5, 3, 2),
                array(1, 1, 0, 2, 0, 5, 9, 9, 5, 4, 7, 8, 9),
                array(1, 6, 0, 5, 9, 9, 1, 9, 0, 5, 4, 7, 1),
                array(1, 0, 4, 0, 0, 3, 2, 4, 1, 6, 4, 6, 5),
                array(1, 7, 3, 7, 3, 3, 7, 6, 1, 7, 7, 8, 6),
                array(0, 9, 1, 7, 3, 5, 1, 8, 9, 3, 8, 6, 2),
                array(1, 4, 9, 9, 3, 7, 5, 4, 4, 4, 4, 4, 0),
                array(0, 3, 7, 7, 4, 3, 6, 1, 1, 3, 5, 1, 6),
                array(0, 8, 5, 4, 3, 9, 3, 3, 1, 3, 4, 8, 1),
                array(1, 6, 1, 9, 4, 6, 4, 6, 4, 5, 2, 1, 5),
                array(1, 1, 1, 6, 8, 3, 9, 1, 1, 3, 0, 9, 9),
                array(0, 5, 1, 6, 8, 4, 8, 8, 2, 4, 4, 9, 2),
                array(0, 2, 3, 0, 1, 4, 2, 7, 1, 9, 9, 0, 6),
                array(0, 8, 4, 2, 5, 1, 4, 9, 5, 2, 0, 4, 3),
                array(0, 9, 1, 2, 5, 0, 6, 6, 5, 0, 3, 1, 8),
                array(1, 7, 8, 7, 1, 7, 4, 6, 3, 3, 3, 3, 9),
                array(0, 3, 7, 2, 9, 4, 1, 5, 4, 7, 2, 1, 0),
                array(1, 2, 8, 1, 1, 6, 4, 7, 8, 2, 0, 5, 2),
                array(1, 8, 3, 5, 4, 8, 0, 9, 7, 8, 0, 1, 8),
                array(1, 7, 9, 9, 0, 4, 5, 7, 2, 9, 0, 1, 9),
                array(0, 6, 6, 5, 6, 7, 0, 4, 0, 7, 8, 5, 1),
                array(0, 6, 0, 6, 3, 1, 1, 5, 0, 9, 2, 2, 3),
                array(1, 6, 3, 5, 6, 7, 1, 6, 6, 9, 7, 4, 9),
                array(0, 9, 5, 9, 8, 2, 4, 3, 3, 2, 3, 5, 6),
                array(0, 1, 6, 3, 8, 9, 9, 2, 8, 2, 5, 8, 6),
                array(1, 4, 7, 6, 6, 5, 7, 3, 3, 3, 4, 1, 1),
                array(1, 8, 2, 9, 0, 3, 8, 6, 8, 3, 3, 7, 3),
                array(0, 2, 8, 4, 8, 5, 4, 8, 9, 5, 0, 5, 7),
            );
            $decodeLngLats = array();
            if (is_string($inputLngLats))
            {
                $encodeLngLats[] = $inputLngLats;
            }
            else if (is_array($inputLngLats))
            {
                $encodeLngLats = $inputLngLats;
            }
            else
            {
                return $decodeLngLats;
            }
            foreach ($encodeLngLats as $encodeLngLat)
            {
                //取编码坐标的后4位字符
                $lenEnLngLat  = strlen($encodeLngLat);
                $lastFourChar = substr($encodeLngLat, $lenEnLngLat - 4);
                //将后4位字符转成对应的ascii值
                $lastFourASCII = array();
                for ($i = 0;$i < strlen($lastFourChar);$i++)
                {
                    $lastFourASCII[] = ord($lastFourChar[$i]);
                }
                //取4个数值的后两位，组成一个新的8位数字，代表key所在的索引值
                $keyPosition = 0;
                $keyPosition |= $lastFourASCII[0]  & 3;
                $keyPosition |= ($lastFourASCII[1] & 3) << 2;
                $keyPosition |= ($lastFourASCII[2] & 3) << 4;
                $keyPosition |= ($lastFourASCII[3] & 3) << 6;
                //根据索引值取对应的key，key是一个长度为13的数组
                $keys = $__keys__[$keyPosition];
                //取编码坐标的去掉最后4位的所有字符
                $LngLat = substr($encodeLngLat, 0, $lenEnLngLat - 4);
                //将所有字符转成对应的ascii值
                $LngLatASCII = array();
                for ($i = 0;$i < strlen($LngLat);$i++)
                {
                    $LngLatASCII[] = ord($LngLat[$i]);
                }
                //根据key的第一位
                $offset = 0;
                switch ($keys[0])
                {
                    case 0:
                        $offset = 23;
                        break;
                    case 1:
                        $offset = 53;
                        break;
                    default:
                        break;
                }
                //将所有的ascii值进行处理
                for ($i = 0;$i < count($LngLatASCII);$i++)
                {
                    $LngLatASCII[$i] -= $offset;
                    $LngLatASCII[$i] -= $keys[$i + 1];
                }
                //将处理后的ascii值转成对应的字符
                $LngLatChar = array();
                for ($i = 0;$i < count($LngLatASCII);$i++)
                {
                    $LngLatChar[] = chr($LngLatASCII[$i]);
                }
                $decodeLngLats[] = implode('', $LngLatChar);
            }
            return $decodeLngLats;
        }
        /**
         * Filter the Name of the Line
         *
         * @param  $lineName string
         * @return string
         */
        public static function filterLineName($lineName)
        {
            $line  = trim($lineName);
            $len   = mb_strlen($line, 'UTF-8');
            $buff  = '';
            $gc    = '';
            $stack = array();
            for ($i = 0;$i < $len;$i++)
            {
                $char = mb_substr($line, $i, 1, 'UTF-8');
                switch ($char)
                {
                    case '(':
                    case '（':
                        array_push($stack, $char);
                        $gc .= $char;
                        break;
                    case ')':
                    case '）':
                        if (count($stack) == 0)
                        {
                            $buff .= $char;
                        }
                        else
                        {
                            array_pop($stack);
                            $gc .= $char;
                        }
                        break;
                    default:
                        if (count($stack) == 0)
                        {
                            $buff .= $char;
                        }
                        else
                        {
                            $gc .= $char;
                        }
                        break;        
                }
            }
            if (count($stack) && strlen($buff) == 0)
            {
                return $gc;
            }
            return $buff;
        }
        /**
         * Get the City Pinyin by Domain
         *
         * @param  $_SERVER['HTTP_HOST']
         * @return string
         */
        public static function getCityByDomain()
        {
            for ($i = 0, $cityPinyin = '';$_SERVER['HTTP_HOST']{$i} != '.' && $_SERVER['HTTP_HOST']{$i};$i++)
            {
                $cityPinyin .= $_SERVER['HTTP_HOST']{$i};
            }
            if (!isset(BusConfig::$BUS_CITYS_MAP[$cityPinyin]))
            {
                $cityPinyin = BusConfig::DEFAULT_CITY_DOMAIN;
            }
            return $cityPinyin;
        }
        /**
         * Get the City Chinese Name and Round City by City Pinyin
         *
         * @param  string  $cityPinyin
         * @param  boolean $isRound
         * @return string
         */
        public static function getCityRelate($cityPinyin = BusConfig::DEFAULT_CITY_DOMAIN, $isRound = true)
        {
            $cityName  = BusConfig::DEFAULT_CITY_CHINESE_NAME;
            $roundCity = array();
            try
            {
                $city = @GeographyManager::getCityPropertyByDomain($cityPinyin);
                if (!is_null($city))
                {
                    $cityName = $city->shortName;
                    if ($isRound)
                    {
                        $province = $city->provinceId * 100;
                        for ($i = 0;$i <= 99;$i++)
                        {
                            if ($i == $city->cityId)
                            {
                                continue;
                            }
                            try
                            {
                                $round = @GeographyManager::getCityPropertyById($province + $i);
                                if (!is_null($round) && isset(BusConfig::$BUS_CITYS_MAP[$round->domain]))
                                {
                                    $roundCity[] = array('cityName' => $round->shortName, 'cityDomain' => $round->domain);
                                }
                                else
                                {
                                    continue;
                                }
                            }
                            catch (Exception $e)
                            {
                                continue;
                            }
                        }
                    }
                }
                else
                {
                   //nothing to do
                }
            }
            catch (Exception $e)
            {
                //nothing to do
            }
            return array($cityName, $roundCity);
        }
        /**
         * Get the Data From the Url by the Curl
         *
         * @param  string func_get_arg(0) the Url of the Data
         * @return string|boolean
         */
        public static function getDataByCurl()
        {
            if (func_num_args() == 1)
            {
                set_time_limit(0);
                if (($curl = @curl_init()) === false)
                {
                    return false;
                }
                $curlOptions              = BusConfig::$CURL_OPTIONS;
                $curlOptions[CURLOPT_URL] = func_get_arg(0);
                if (@curl_setopt_array($curl, $curlOptions) === false)
                {
                    return false;
                }
                if (($result = @curl_exec($curl)) === false)
                {
                    return false;
                }
                @curl_close($curl);
                return $result;
            }
            else
            {
                return false;
            }
        }
        /**
         * Get Data From Xml Object
         *
         * @param  resource $bus
         * @return array|boolean
         */
        public static function getDataFromXml($bus)
        {
            $pinyin    = new ChineseStringToPinYin;
            $pinyin->setChineseString(strval($bus->name));
            $basic     = array(
                'line_id'    => strval($bus->line_id),
                'assort_key' => strtolower($pinyin->getFirstChar()),
                'line_name'  => mb_check_encoding(strval($bus->name), 'UTF-8') ? strval($bus->name) : iconv('GBK', 'UTF-8', strval($bus->name)),
            );
            $attribute = array(
                'line_id'            => strval($bus->line_id),
                'type'               => intval($bus->type),
                'status'             => intval($bus->status),
                'start_time'         => strlen(strval($bus->start_time)) ? strval($bus->start_time) : BusDataCrawlerConfig::DEFAULT_START_TIME,
                'end_time'           => strlen(strval($bus->end_time)) ? strval($bus->end_time) : BusDataCrawlerConfig::DEFAULT_END_TIME,
                'interval_time'      => strlen(strval($bus->interval1)) ? strval($bus->interval1) : BusDataCrawlerConfig::DEFAULT_INTERVAL,
                'commutation_ticket' => intval($bus->commutation_ticket),
                'air'                => intval($bus->air),
                'auto'               => intval($bus->auto),
                'ic_card'            => intval($bus->ic_card),
                'express_way'        => intval($bus->express_way),
                'double_deck'        => intval($bus->double_deck),
                'data_source'        => intval($bus->data_source),
                'loop_line'          => intval($bus->loop),
                'length'             => floatval($bus->length),
                'basic_price'        => floatval($bus->basic_price),
                'total_price'        => floatval($bus->total_price),
                'start_name'         => mb_check_encoding(strval($bus->front_name), 'UTF-8') ? strval($bus->front_name) : iconv('GBK', 'UTF-8', strval($bus->front_name)),
                'start_spell'        => str_replace(array_values(BusDataCrawlerConfig::$DEFAULT_SEARCH), array_keys(BusDataCrawlerConfig::$DEFAULT_SEARCH), strval($bus->front_spell)),
                'end_name'           => mb_check_encoding(strval($bus->terminal_name), 'UTF-8') ? strval($bus->terminal_name) : iconv('GBK', 'UTF-8', strval($bus->terminal_name)),
                'end_spell'          => str_replace(array_values(BusDataCrawlerConfig::$DEFAULT_SEARCH), array_keys(BusDataCrawlerConfig::$DEFAULT_SEARCH), strval($bus->terminal_spell)),
                'service_period'     => mb_check_encoding(strval($bus->service_period), 'UTF-8') ? strval($bus->service_period) : iconv('GBK', 'UTF-8', strval($bus->service_period)),
                'company'            => strlen(strval($bus->company)) ? (mb_check_encoding(strval($bus->company), 'UTF-8') ? strval($bus->company) : iconv('GBK', 'UTF-8', strval($bus->company))) : BusDataCrawlerConfig::DEFAULT_COMPANY,
                'xys'                => strval($bus->xys),
            );
            $station   = array(
                'line_id'       => strval($bus->line_id),
                'assort_key'    => '',
                'Code'          => '',
                'station_num'   => 0,
                'station_name'  => '',
                'station_spell' => '',
                'lngX'          => '',
                'latY'          => '',
            );
            $stationsXml = @simplexml_load_string($bus->stationdes, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($stationsXml === false)
            {
                return false;
            }
            $stations = array();
            foreach ($stationsXml->STATION as $item)
            {
                $newStation                  = $station;
				$stationSpell                = str_replace(array_values(BusDataCrawlerConfig::$DEFAULT_SEARCH), array_keys(BusDataCrawlerConfig::$DEFAULT_SEARCH), strval($item->DATA[2]));
                $newStation['assort_key']    = strtolower(substr($stationSpell, 0, 1));
                $newStation['Code']          = strval($item->DATA[3]);
                $newStation['station_num']   = intval($item->DATA[4]);
                $newStation['station_name']  = strval($item->DATA[0]);
                $newStation['station_spell'] = $stationSpell;
                list($newStation['lngX'], $newStation['latY']) = split(';', strval($item->DATA[1]), 2);
                $stations[] = $newStation;
            }
            return array(
                'basic'     => $basic,
                'attribute' => $attribute,
                'station'   => $stations,
            );
        }
        /**
         * Get Transfer Data From Xml
         *
         * @param  string $xml
         * @return mixed
         */
        public static function getTransferFromXml($xml)
        {
            if ($xml === false)
            {
                return false;
            }
            $xml    = str_replace("GBK", "UTF-8", strval($xml));
            $xml    = mb_convert_encoding($xml, 'UTF-8', 'GBK');
            $result = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($result === false || $result->count == 0)
            {
                return false;
            }
            $data    = array();
            $item    = array(
                'segmentCount' => 0,
                'depotCount'   => 0,
                'length'       => 0,
                'segments'     => array(),
            );
            $segment = array(
                'start'    => '',
                'start_id' => '',
                'end'      => '',
                'end_id'   => '',
                'line'     => '',
                'line_id'  => '',
            );
            $dbObj = new BusSearchDb();
            foreach ($result->busList->bus as $bus)
            {
                if (count($bus->segmentList->segment) > 3)
                {
                    continue;
                }
                $temp = $item;
                $temp['segmentCount'] = count($bus->segmentList->segment);
                foreach ($bus->segmentList->segment as $element)
                {
                    $temp['depotCount'] += intval($element->passDepotCount);
                    $temp['length']     += intval($element->driverLength);
                    $tempSeg = $segment;
                    $tempSeg['start']    = strval($element->startName);
                    $tempSeg['end']      = strval($element->endName);
                    $tempSeg['line']     = BusUtils::filterLineName(strval($element->busName));
                    $tempSeg['start_id'] = $dbObj->getStationIdByName($tempSeg['start']);
                    $tempSeg['end_id']   = $dbObj->getStationIdByName($tempSeg['end']);
                    $tempSeg['line_id']  = $dbObj->getLineIdByName($tempSeg['line']);
                    $temp['segments'][]  = $tempSeg;
                }
                $data[] = $temp;
            }
            return count($data) ? $data : count($data);
        }
        /**
         * Sort the Station Array by the Relate of the Station Name
         *
         * @param  array $data
         * @return array
         */
        public static function relateSort($data, $keyword)
        {
            $match  = array();
            $first  = array();
            $relate = array();
            $length = strlen($keyword);
            foreach ($data as $item)
            {
                if (strcmp($item['station_name'], $keyword) === 0)
                {
                    $match[] = $item;
                }
                else if (strncmp($item['station_name'], $keyword, $length) === 0)
                {
                    $first[] = $item;
                }
                else
                {
                    $relate[] = $item;
                }
            }
            return array_merge($match, $first, $relate);
        }
        /**
         * Rewrite to the Url
         *
         * @param  string $url
         * @return void
         */
        public static function rewriteUrl($url)
        {
            header('HTTP/1.1 302 Found');
            header("Location: $url");
        }
        public static function lineIdToString($lineId)
        {
            $len = strlen($lineId);
            $str = '';
            for ($i = $len - 1;$i >= $len - 6;$i--)
            {
                $str = chr(ord($lineId{$i}) + 49) . $str;
            }
            return $str;
        }
        public static function stringToLineId($string)
        {
            $str = '';
            $len = strlen($string);
            for ($i = 0;$i < $len;$i++)
            {
                $str .= chr(ord($string{$i}) - 49);
            }
            return $str;
        }
        public static function numToString($num)
        {
            $str = '';
            $len = strlen($num);
            for ($i = 0;$i < $len;$i++)
            {
                $str .= chr(ord($num{$i}) + 49);
            }
            return $str;
        }
    }