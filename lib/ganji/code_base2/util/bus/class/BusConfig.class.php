<?php
if(!class_exists('BusConfig')){
    /**
     * 公交通用配置类
     *
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */

    class BusConfig
    {
        /**
         * the Map of the Bus Code of the City Domain
         *
         * @staticvar array
         */
        public static $BUS_CITYS_MAP     = array(
            //四个直辖市
            'bj'           => array('cityCode' => '010',  'onService' => true, 'prefix' => '110100'),
            'sh'           => array('cityCode' => '021',  'onService' => true, 'prefix' => '310100'),
            'tj'           => array('cityCode' => '022',  'onService' => true, 'prefix' => '120100'),
            'cq'           => array('cityCode' => '023',  'onService' => true, 'prefix' => '500100'),
            //海南省
            'sanya'        => array('cityCode' => '0899', 'onService' => true, 'prefix' => '110100'),
            'hn'           => array('cityCode' => '0898', 'onService' => true, 'prefix' => '460100'),
            //福建省
            'sanming'      => array('cityCode' => '0598', 'onService' => true, 'prefix' => '110100'),
            'nanping'      => array('cityCode' => '0599', 'onService' => true, 'prefix' => '110100'),
            'xm'           => array('cityCode' => '0592', 'onService' => true, 'prefix' => '350200'),
            'ningde'       => array('cityCode' => '0593', 'onService' => true, 'prefix' => '110100'),
            'quanzhou'     => array('cityCode' => '0595', 'onService' => true, 'prefix' => '110100'),
            'zhangzhou'    => array('cityCode' => '0596', 'onService' => true, 'prefix' => '110100'),
            'putian'       => array('cityCode' => '0594', 'onService' => true, 'prefix' => '110100'),
            'fz'           => array('cityCode' => '0591', 'onService' => true, 'prefix' => '350100'),
            //河南省
            'anyang'       => array('cityCode' => '0372', 'onService' => true, 'prefix' => '110100'),
            'pingdingshan' => array('cityCode' => '0375', 'onService' => true, 'prefix' => '110100'),
            'luoyang'      => array('cityCode' => '0379', 'onService' => true, 'prefix' => '110100'),
            'jiaozuo'      => array('cityCode' => '0391', 'onService' => true, 'prefix' => '110100'),
            'zz'           => array('cityCode' => '0371', 'onService' => true, 'prefix' => '410100'),
            'hebi'         => array('cityCode' => '0392', 'onService' => true, 'prefix' => '110100'),
            'xinxiang'     => array('cityCode' => '0373', 'onService' => true, 'prefix' => '110100'),
            //江西省
            'nc'           => array('cityCode' => '0791', 'onService' => true, 'prefix' => '360100'),
            'jingdezhen'   => array('cityCode' => '0798', 'onService' => true, 'prefix' => '110100'),
            'jiujiang'     => array('cityCode' => '0792', 'onService' => true, 'prefix' => '110100'),
            //广东省
            'gz'           => array('cityCode' => '020',  'onService' => true, 'prefix' => '440100'),
            'dg'           => array('cityCode' => '0769', 'onService' => true, 'prefix' => '441900'),
            'foshan'       => array('cityCode' => '0757', 'onService' => true, 'prefix' => '110100'),
            'huizhou'      => array('cityCode' => '0752', 'onService' => true, 'prefix' => '110100'),
            'shantou'      => array('cityCode' => '0754', 'onService' => true, 'prefix' => '110100'),
            'jiangmen'     => array('cityCode' => '0750', 'onService' => true, 'prefix' => '110100'),
            'sz'           => array('cityCode' => '0755', 'onService' => true, 'prefix' => '440300'),
            'maoming'      => array('cityCode' => '0668', 'onService' => true, 'prefix' => '110100'),
            'zhuhai'       => array('cityCode' => '0756', 'onService' => true, 'prefix' => '110100'),
            'zhongshan'    => array('cityCode' => '0760', 'onService' => true, 'prefix' => '110100'),
            //辽宁省
            'dl'           => array('cityCode' => '0411', 'onService' => true, 'prefix' => '210200'),
            'sy'           => array('cityCode' => '024',  'onService' => true, 'prefix' => '210100'),
            'panjin'       => array('cityCode' => '0427', 'onService' => true, 'prefix' => '110100'),
            'yingkou'      => array('cityCode' => '0417', 'onService' => true, 'prefix' => '110100'),
            'huludao'      => array('cityCode' => '0429', 'onService' => true, 'prefix' => '110100'),
            'liaoyang'     => array('cityCode' => '0419', 'onService' => true, 'prefix' => '110100'),
            'jinzhou'      => array('cityCode' => '0416', 'onService' => true, 'prefix' => '110100'),
            'dandong'      => array('cityCode' => '0415', 'onService' => true, 'prefix' => '110100'),
            'fushun'       => array('cityCode' => '0413', 'onService' => true, 'prefix' => '110100'),
            'anshan'       => array('cityCode' => '0412', 'onService' => true, 'prefix' => '110100'),
            //甘肃省
            'lz'           => array('cityCode' => '0931', 'onService' => true, 'prefix' => '620100'),
            //山西省
            'ty'           => array('cityCode' => '0351', 'onService' => true, 'prefix' => '140100'),
            //山东省
            'wei'          => array('cityCode' => '0631', 'onService' => true, 'prefix' => '371000'),
            'zaozhuang'    => array('cityCode' => '0632', 'onService' => true, 'prefix' => '110100'),
            'jn'           => array('cityCode' => '0531', 'onService' => true, 'prefix' => '370100'),
            'zibo'         => array('cityCode' => '0533', 'onService' => true, 'prefix' => '110100'),
            'binzhou'      => array('cityCode' => '0543', 'onService' => true, 'prefix' => '110100'),
            'yantai'       => array('cityCode' => '0535', 'onService' => true, 'prefix' => '110100'),
            'laiwu'        => array('cityCode' => '0634', 'onService' => true, 'prefix' => '110100'),
            'qd'           => array('cityCode' => '0532', 'onService' => true, 'prefix' => '370200'),
            'dongying'     => array('cityCode' => '0546', 'onService' => true, 'prefix' => '110100'),
            'weifang'      => array('cityCode' => '0536', 'onService' => true, 'prefix' => '110100'),
            //云南省
            'yuxi'         => array('cityCode' => '0877', 'onService' => true, 'prefix' => '110100'),
            'km'           => array('cityCode' => '0871', 'onService' => true, 'prefix' => '530100'),
            //浙江省
            'jiaxing'      => array('cityCode' => '0573', 'onService' => true, 'prefix' => '110100'),
            'nb'           => array('cityCode' => '0574', 'onService' => true, 'prefix' => '330200'),
            'hz'           => array('cityCode' => '0571', 'onService' => true, 'prefix' => '330100'),
            'wenzhou'      => array('cityCode' => '0577', 'onService' => true, 'prefix' => '110100'),
            'huzhou'       => array('cityCode' => '0572', 'onService' => true, 'prefix' => '110100'),
            'shaoxing'     => array('cityCode' => '0575', 'onService' => true, 'prefix' => '110100'),
            'zhoushan'     => array('cityCode' => '0580', 'onService' => true, 'prefix' => '110100'),
            'zjtaizhou'    => array('cityCode' => '0576', 'onService' => true, 'prefix' => '110100'),
            'jinhua'       => array('cityCode' => '0579', 'onService' => true, 'prefix' => '110100'),
            //内蒙古
            'baotou'       => array('cityCode' => '0472', 'onService' => true, 'prefix' => '110100'),
            'nmg'          => array('cityCode' => '0471', 'onService' => true, 'prefix' => '150100'),
            //新疆
            'xj'           => array('cityCode' => '0991', 'onService' => true, 'prefix' => '650100'),
            //四川省
            'leshan'       => array('cityCode' => '0833', 'onService' => true, 'prefix' => '110100'),
            'yibin'        => array('cityCode' => '0831', 'onService' => true, 'prefix' => '110100'),
            'cd'           => array('cityCode' => '028',  'onService' => true, 'prefix' => '510100'),
            'mianyang'     => array('cityCode' => '0816', 'onService' => true, 'prefix' => '110100'),
            'zigong'       => array('cityCode' => '0813', 'onService' => true, 'prefix' => '110100'),
            'luzhou'       => array('cityCode' => '0830', 'onService' => true, 'prefix' => '110100'),
            //安徽省
            'hf'           => array('cityCode' => '0551', 'onService' => true, 'prefix' => '340100'),
            'anqing'       => array('cityCode' => '0556', 'onService' => true, 'prefix' => '110100'),
            'chaohu'       => array('cityCode' => '0565', 'onService' => true, 'prefix' => '110100'),
            'wuhu'         => array('cityCode' => '0553', 'onService' => true, 'prefix' => '110100'),
            'bengbu'       => array('cityCode' => '0552', 'onService' => true, 'prefix' => '110100'),
            'maanshan'     => array('cityCode' => '0555', 'onService' => true, 'prefix' => '110100'),
            //黑龙江省
            'jiamusi'      => array('cityCode' => '0454', 'onService' => true, 'prefix' => '110100'),
            'hrb'          => array('cityCode' => '0451', 'onService' => true, 'prefix' => '230100'),
            'daqing'       => array('cityCode' => '0459', 'onService' => true, 'prefix' => '110100'),
            'hljyichun'    => array('cityCode' => '0458', 'onService' => true, 'prefix' => '110100'),
            'qiqihaer'     => array('cityCode' => '0452', 'onService' => true, 'prefix' => '110100'),
            //河北省
            'baoding'      => array('cityCode' => '0312', 'onService' => true, 'prefix' => '110100'),
            'tangshan'     => array('cityCode' => '0315', 'onService' => true, 'prefix' => '130200'),
            'langfang'     => array('cityCode' => '0316', 'onService' => true, 'prefix' => '110100'),
            'zhangjiakou'  => array('cityCode' => '0313', 'onService' => true, 'prefix' => '110100'),
            'chengde'      => array('cityCode' => '0314', 'onService' => true, 'prefix' => '110100'),
            'sjz'          => array('cityCode' => '0311', 'onService' => true, 'prefix' => '130100'),
            'qinhuangdao'  => array('cityCode' => '0335', 'onService' => true, 'prefix' => '110100'),
            'handan'       => array('cityCode' => '0310', 'onService' => true, 'prefix' => '110100'),
            //贵州省
            'gy'           => array('cityCode' => '0851', 'onService' => true, 'prefix' => '520100'),
            //广西省
            'nn'           => array('cityCode' => '0771', 'onService' => true, 'prefix' => '450100'),
            'liuzhou'      => array('cityCode' => '0772', 'onService' => true, 'prefix' => '110100'),
            'baise'        => array('cityCode' => '0776', 'onService' => true, 'prefix' => '110100'),
            'gl'           => array('cityCode' => '0773', 'onService' => true, 'prefix' => '450300'),
            //湖北省
            'shiyan'       => array('cityCode' => '0719', 'onService' => true, 'prefix' => '110100'),
            'yichang'      => array('cityCode' => '0717', 'onService' => true, 'prefix' => '110100'),
            'wh'           => array('cityCode' => '027',  'onService' => true, 'prefix' => '420100'),
            'jingzhou'     => array('cityCode' => '0716', 'onService' => true, 'prefix' => '110100'),
            'jingmen'      => array('cityCode' => '0724', 'onService' => true, 'prefix' => '110100'),
            'xiangyang'    => array('cityCode' => '0710', 'onService' => true, 'prefix' => '110100'),
            'huangshi'     => array('cityCode' => '0714', 'onService' => true, 'prefix' => '110100'),
            //江苏省
            'nj'           => array('cityCode' => '025',  'onService' => true, 'prefix' => '320100'),
            'su'           => array('cityCode' => '0512', 'onService' => true, 'prefix' => '320500'),
            'nantong'      => array('cityCode' => '0513', 'onService' => true, 'prefix' => '110100'),
            'changzhou'    => array('cityCode' => '0519', 'onService' => true, 'prefix' => '110100'),
            'xuzhou'       => array('cityCode' => '0516', 'onService' => true, 'prefix' => '110100'),
            'yangzhou'     => array('cityCode' => '0514', 'onService' => true, 'prefix' => '110100'),
            'wx'           => array('cityCode' => '0510', 'onService' => true, 'prefix' => '320200'),
            'yancheng'     => array('cityCode' => '0515', 'onService' => true, 'prefix' => '110100'),
            'lianyungang'  => array('cityCode' => '0518', 'onService' => true, 'prefix' => '110100'),
            //吉林省
            'jilin'        => array('cityCode' => '0432', 'onService' => true, 'prefix' => '110100'),
            'siping'       => array('cityCode' => '0434', 'onService' => true, 'prefix' => '110100'),
            'yanbian'      => array('cityCode' => '0433', 'onService' => true, 'prefix' => '110100'),
            'cc'           => array('cityCode' => '0431', 'onService' => true, 'prefix' => '220100'),
            //陕西省
            'xianyang'     => array('cityCode' => '0910', 'onService' => true, 'prefix' => '110100'),
            'shangluo'     => array('cityCode' => '0914', 'onService' => true, 'prefix' => '110100'),
            'ankang'       => array('cityCode' => '0915', 'onService' => true, 'prefix' => '110100'),
            'baoji'        => array('cityCode' => '0917', 'onService' => true, 'prefix' => '110100'),
            'xa'           => array('cityCode' => '029',  'onService' => true, 'prefix' => '610100'),
            'yanan'        => array('cityCode' => '0911', 'onService' => true, 'prefix' => '110100'),
            //湖南省
            'changde'      => array('cityCode' => '0736', 'onService' => true, 'prefix' => '110100'),
            'zhuzhou'      => array('cityCode' => '0733', 'onService' => true, 'prefix' => '110100'),
            'xiangtan'     => array('cityCode' => '0732', 'onService' => true, 'prefix' => '110100'),
            'cs'           => array('cityCode' => '0731', 'onService' => true, 'prefix' => '430100'),
            'hengyang'     => array('cityCode' => '0734', 'onService' => true, 'prefix' => '110100'),
            'yueyang'      => array('cityCode' => '0730', 'onService' => true, 'prefix' => '110100'),
            //西藏
            'xz'           => array('cityCode' => '0891', 'onService' => true, 'prefix' => '540100'),
            //青海省
            'xn'           => array('cityCode' => '0971', 'onService' => true, 'prefix' => '630100'),
            //宁夏
            'yc'           => array('cityCode' => '0951', 'onService' => true, 'prefix' => '640100'),
        );
        /**
         * the Options of the CURL Object
         *
         * @staticvar array
         */
        public static $CURL_OPTIONS      = array(
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_HTTPGET        => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL            => '',
        );
        /**
         * the Important Cities
         *
         * @staticvar array
         */
        public static $MAJOR_CITY       = array(
            '北京'  => 'bj',
            '上海'  => 'sh',
            '天津'  => 'tj',
            '重庆'  => 'cq',
            '广州'  => 'gz',
            '深圳'  => 'sz',
            '东莞'  => 'dg',
            '成都'  => 'cd',
            '杭州'  => 'hz',
            '宁波'  => 'nb',
            '沈阳'  => 'sy',
            '大连'  => 'dl',
            '南京'  => 'nj',
            '福州'  => 'fz',
            '厦门'  => 'xm',
            '石家庄' => 'sjz',
            '郑州'  => 'zz',
            '长春'  => 'cc',
            '哈尔滨' => 'hrb',
            '济南'  => 'jn',
            '青岛'  => 'qd',
            '合肥'  => 'hn',
            '南宁'  => 'nn',
            '太原'  => 'ty',
            '兰州'  => 'lz',
            '西安'  => 'xa',
            '武汉'  => 'wh',
            '长沙'  => 'cs',
            '昆明'  => 'km',
        );
        /**
         * Default Value of the Cache Prefix
         *
         * @staticvar string
         */
        const DEFAULT_CACHE_PREFIX       = 'bus';
        /**
         * Default Value of the Cache Split Char
         *
         * @staticvar string
         */
        const DEFAULT_CACHE_SPLIT_CHAR   = '_';
        /**
         * Default Value of the City Domain
         *
         * @staticvar string
         */
        const DEFAULT_CITY_DOMAIN        = 'bj';
        /**
         * Default Value of the City Chinese Name
         *
         * @staticvar string
         */
        const DEFAULT_CITY_CHINESE_NAME  = '北京';
        /**
         * Default Value of the Request Url for Friend Link
         *
         * @staticvar string
         */
        const DEFAULT_REQUEST_URL        = 'http://%s.ganji.com/bus/';
        /**
         * Default Value of the Search Url for the Bus Name
         *
         * @staticvar string
         */
        const DEFAULT_SEARCH_BUSNAME_URL = 'http://search1.mapabc.com/sisserver?&config=BusLine&cityCode=%s&busName=%s&resData=3&a_k=4726f4e7aa32db60204e6c05d7e41ada440055e47021072e45322d670324223eedbbd89f7b0788d2';
        /**
         * Default Value of the Search Url for the Bus Line Id
         *
         * @staticvar string
         */
        const DEFAULT_SEARCH_BUSLINE_URL = 'http://search1.mapabc.com/sisserver?&config=BusLine&cityCode=%s&ids=%s&resData=3&a_k=4726f4e7aa32db60204e6c05d7e41ada440055e47021072e45322d670324223eedbbd89f7b0788d2';
        /**
         * Default Value of the Search Url for the Bus Line Switch
         *
         * @staticvar string
         */
        const DEFAULT_SWITCH_BUSLINE_URL = 'http://search1.mapabc.com/sisserver?&config=BR&cityCode=%s&x1=%s&y1=%s&x2=%s&y2=%s&ver=2.0&routeType=%d&a_k=4726f4e7aa32db60204e6c05d7e41ada440055e47021072e45322d670324223eedbbd89f7b0788d2';
        /**
         * the Json of the Map of the Cities of Every Province
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_JSON = 'var provinces = {"北京":0,"上海":0,"天津":0,"重庆":0,"安徽":1,"福建":2,"甘肃":3,"广东":4,"广西":5,"贵州":6,"海南":7,"河北":8,"河南":9,"黑龙江":10,"湖北":11,"湖南":12,"江苏":13,"江西":14,"吉林":15,"辽宁":16,"内蒙古":17,"宁夏":18,"青海":19,"山东":20,"山西":21,"陕西":22,"四川":23,"新疆":24,"西藏":25,"云南":26,"浙江":27};var citys = {"1":["合肥","安庆","巢湖","芜湖","蚌埠","马鞍山"],"2":["三明","南平","厦门","宁德","泉州","漳州","莆田","福州"],"3":["兰州"],"4":["广州","东莞","佛山","惠州","汕头","江门","深圳","茂名","珠海","中山"],"5":["南宁","柳州","百色","桂林"],"6":["贵阳"],"7":["三亚","海口"],"8":["保定","唐山","廊坊","张家口","承德","石家庄","秦皇岛","邯郸"],"9":["安阳","平顶山","洛阳","焦作","郑州","鹤壁","新乡"],"10":["佳木斯","哈尔滨","大庆","伊春","齐齐哈尔"],"11":["十堰","宜昌","武汉","荆州","荆门","襄樊","黄石"],"12":["常德","株洲","湘潭","长沙","衡阳","岳阳"],"13":["南京","苏州","南通","常州","徐州","扬州","无锡","盐城","连云港"],"14":["南昌","景德镇","九江"],"15":["吉林","四平","延边","长春"],"16":["大连","沈阳","盘锦","营口","葫芦岛","辽阳","锦州","丹东","抚顺","鞍山"],"17":["包头","呼和浩特"],"18":["银川"],"19":["西宁"],"20":["威海","枣庄","济南","淄博","滨州","烟台","莱芜","青岛","东营","潍坊"],"21":["太原"],"22":["咸阳","商洛","安康","宝鸡","西安","延安"],"23":["乐山","宜宾","成都","绵阳","自贡","泸州"],"24":["乌鲁木齐"],"25":["拉萨"],"26":["玉溪","昆明"],"27":["嘉兴","宁波","杭州","温州","湖州","绍兴","舟山","台州","金华"]};var pinyinMap = {"北京":"bj","上海":"sh","天津":"tj","重庆":"cq","合肥":"hf","安庆":"anqing","巢湖":"chaohu","芜湖":"wuhu","蚌埠":"bengbu","马鞍山":"maanshan","三明":"sanming","南平":"nanping","厦门":"xm","宁德":"ningde","泉州":"quanzhou","漳州":"zhangzhou","莆田":"putian","福州":"fz","兰州":"lz","广州":"gz","东莞":"dg","佛山":"foshan","惠州":"huizhou","汕头":"shantou","江门":"jiangmen","深圳":"sz","茂名":"maoming","珠海":"zhuhai","中山":"zhongshan","南宁":"nn","柳州":"liuzhou","百色":"baise","桂林":"gl","贵阳":"gy","三亚":"sanya","海口":"hn","保定":"baoding","唐山":"tangshan","廊坊":"langfang","张家口":"zhangjiakou","承德":"chengde","石家庄":"sjz","秦皇岛":"qinhuangdao","邯郸":"handan","安阳":"anyang","平顶山":"pingdingshan","洛阳":"luoyang","焦作":"jiaozuo","郑州":"zz","鹤壁":"hebi","新乡":"xinxiang","佳木斯":"jiamusi","哈尔滨":"hrb","大庆":"daqing","伊春":"hljyichun","齐齐哈尔":"qiqihaer","十堰":"shiyan","宜昌":"yichang","武汉":"wh","荆州":"jingzhou","荆门":"jingmen","襄樊":"xiangfan","黄石":"huangshi","常德":"changde","株洲":"zhuzhou","湘潭":"xiangtan","长沙":"cs","衡阳":"hengyang","岳阳":"yueyang","南京":"nj","苏州":"su","南通":"nantong","常州":"changzhou","徐州":"xuzhou","扬州":"yangzhou","无锡":"wx","盐城":"yancheng","连云港":"lianyungang","南昌":"nc","景德镇":"jingdezhen","九江":"jiujiang","吉林":"jilin","四平":"siping","延边":"yanbian","长春":"cc","大连":"dl","沈阳":"sy","盘锦":"panjin","营口":"yingkou","葫芦岛":"huludao","辽阳":"liaoyang","锦州":"jinzhou","丹东":"dandong","抚顺":"fushun","鞍山":"anshan","包头":"baotou","呼和浩特":"nmg","银川":"yc","西宁":"xn","威海":"wei","枣庄":"zaozhuang","济南":"jn","淄博":"zibo","滨州":"binzhou","烟台":"yantai","莱芜":"laiwu","青岛":"qd","东营":"dongying","潍坊":"weifang","太原":"ty","咸阳":"xianyang","商洛":"shangluo","安康":"ankang","宝鸡":"baoji","西安":"xa","延安":"yanan","乐山":"leshan","宜宾":"yibin","成都":"cd","绵阳":"mianyang","自贡":"zigong","泸州":"luzhou","乌鲁木齐":"xj","拉萨":"xz","玉溪":"yuxi","昆明":"km","嘉兴":"jiaxing","宁波":"nb","杭州":"hz","温州":"wenzhou","湖州":"huzhou","绍兴":"shaoxing","舟山":"zhoushan","台州":"zjtaizhou","金华":"jinhua"};';
    }
}
