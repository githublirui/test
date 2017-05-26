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
    
    /**
     * 各个调用类别唯一的类型
     * 
     * 小区：xiaoqu
     * 房产竞价：ext
     * 商铺出租/求租：storerent
     * 商铺出售/求购：storetrade
     * 写字楼出租/求租：officerent
     * 写字楼出售/求购：officetrade
     * 厂房：plant
     * 出租房：rent
     * 合租房：share
     * 二手房出售：sell
     * 求租房：wantrent
     * 二手房求购：wantbuy
     * 日租短租：shortrent
     */
    $type  = 'ext';
    /**
     * 帖子id
     */
    $id    = 170831;
    /**
     * 经度坐标
     */
    $lngX  = 116.31714820861816;
    /**
     * 纬度坐标
     */
    $latY  = 39.97073990227103;
    /**
     * 调用ditu_traffic_page时的query_string，必须严格按照调用时的顺序，需要对该字段进行md5校验
     */
    $query = 'lnglat=116.31714820861816,39.97073990227103&city=' . urlencode('北京') . '&address=' . urlencode('海淀南路') . '&pop&left=340,340&right=340,340&crossdomain=1&type=ext&id=170831';
    /**
     * 城市中文名，默认为北京
     */
    $city  = '北京';
    /**
     * 取距离中心点多少米范围内的公交线路，默认为1000米
     */
    $range = 1000;
    /**
     * 返回去重后的公交线路信息，数据格式如下
     * 
     * array(
     *     公交线路名 => 公交线路url
     * )
     * 
     * 其中地铁排在前，公交排在后，按距中心点的距离由近到远排序
     */
    echo '<strong>内部调用接口：</strong><br/><br/>';
    var_dump(DituRoundBus::getRoundBus($type, $id, $lngX, $latY, $query, $city, $range));
    /**
     * http的服务接口，为房产竞价和小区服务
     * 在请求该服务时，访问参数应与调用ditu_traffic_page时完全一致，包括参数顺序和参数值
     * 在请求该服务时，需要保证域名的一致性，即用户访问xxx.ganji.com，则调用该http服务时，也同样使用对应的xxx.ganji.com域名
     * 该接口会返回序列化后的数组，数组内容和内部调用接口返回的数组内容一致
     */
    echo '<br/><br/><strong>http服务接口：</strong><br/><br/>';
    $url = 'http://' . HttpHandler::getDomain() . '.ganji.com/ditu/ditu_getroundbus_page.php?lnglat=116.31714820861816,39.97073990227103&city=' . urlencode('北京') . '&address=' . urlencode('海淀南路') . '&pop&left=340,340&right=340,340&crossdomain=1&type=ext&id=170831';
    set_time_limit(0);
    if (($curl = @curl_init()) === false)
    {
        echo 'curl_init error';
        exit;
    }
    $curlOptions = array(
        CURLOPT_CONNECTTIMEOUT => 60,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPGET        => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL            => $url,
    );    
    if (@curl_setopt_array($curl, $curlOptions) === false)
    {
        echo 'curl_setopt_array error';
        exit;
    }
    if (($data = @curl_exec($curl)) === false)
    {
        echo 'curl_exec error';
        exit;
    }
    @curl_close($curl);
    var_dump(unserialize($data));