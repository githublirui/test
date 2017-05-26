<?php
/**
 * v3的数据库配置，v5中严禁使用，仅为了并存期间解决命名冲突
 */
require_once dirname(__FILE__) . '/DBConfigV3.class.php';

/**
 * 数据库配置类
 */
class DBConfig extends DBConfigV3 {

   /**
    * 数据库名称
    */
    const DB_CRM            = "gcrm";
    const DB_JIAOYOU        = 'jiaoyou';
    const DB_JIAOYOU_BROWSE = 'jiaoyou_browse';
    const DB_JIAOYOU_MESSAGE= 'jiaoyou_message';
    const DB_JIAOYOU_APP    = "jiaoyou_app";
    const DB_JIAOYOU_MOOD   = "jiaoyou_mood";
    const DB_JIAOYOU_LOGIN  = "jiaoyou_login";
    const DB_JIAOYOU_BUSINESS = "jiaoyou_business";
    const DB_JIAOYOU_GIFT     = 'jiaoyou_gift';
    const DB_JIAOYOU_CIRCLE   = 'jiaoyou_circle';
    const DB_JIAOYOU_STAT     = 'jiaoyou_stat';
    const DB_PPC            = "ppc";
    const DB_CT             = "ct";
    const DB_REWARD         = 'reward';
    const DB_BANG           = 'bang';
    const DB_GANJI_PET      = 'ganji_pet';
    const DB_SELF_TOP = 'biz_top';
    const DB_POST_REFRESH_LOG = 'post_refresh_log';//帖子刷新日志库
    const DB_SECONDMARKET = 'ganji_secondmarket';
    const DB_ZUCHE = 'zuche'; //赶集租车
    const DB_GANJI_REPORT = 'ganji_report'; //统计库
    const DB_GANJI_FORUM = 'ganji_forum';
    const DB_WIDGET = 'widget'; // 添加客户端客户端订阅相关的库名
    const DB_PAY    = 'ucv2_main';  //支付
    const DB_GANJI_MISC= 'ganji_misc'; //统一审核
    const DB_GANJI_SERVICE_BID = 'ganji_service_bid'; //服务招投标库
	const DB_SIMPLE_LIVES = 'simple_lives'; //C2B手机APP库
    const DB_SIMPLE_LIVES_REPORTING = 'simple_lives_reporting'; // C2B统计库
    const DB_SIMPLE_LIVES_AUDIT = 'simple_lives_audit'; // C2B审核库

    const DB_CALL_TRANSFER_LOCAL = 'ct'; //赶集本地机房 calltransfer
    const DB_GANJI_VEHICLE = 'ganji_vehicle';

    // 通用的一些常量
    const COMMON_HOST     = '10.3.255.21'; //zgc-off-ku-qa
    //const COMMON_HOST     = '192.168.129.21'; //zgc-off-ku-qa
    const COMMON_USERNAME = 'off_dbsrt';
    const COMMON_PASSWD   = '65c16c8b6';
    const COMMON_PORT     = '3310';

    const REAL_HOST     = '192.168.116.20';
    const REAL_USERNAME = 'zhoufan';
    const REAL_PASSWD   = 'fe226902a';

    public static $SERVER_MS_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    public static $SERVER_MS_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    public static $SERVER_MS_REAL = array(
        'host'      => self::REAL_HOST,
        'username'  => self::REAL_USERNAME,
        'password'  => self::REAL_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    public static $SERVER_MS_SLOW_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );



    //招聘慢库
    public static $SERVER_MS_JOB_SLOW_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    public static $SERVER_HOUSING_PREMIER_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3328',
    );
    public static $SERVER_HOUSING_PREMIER_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3328',
    );
    public static $SERVER_HOUSING_PREMIER_SLOW_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3328',
    );
    public static $SERVER_MANAGEMENT_MASTER = array(
        'host'      => '10.3.255.21',
        'username'  => 'off_mymsc',
        'password'  => 'hpctiEepEIty',
        'port'      => '3311',
    );
    public static $SERVER_MANAGEMENT_SLAVE = array(
        'host'      => '10.3.255.21',
        'username'  => 'off_mymsc_r',
        'password'  => 'JHAS129sioq1',
        'port'      => '3311',
    );
    public static $SERVER_MISC_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    public static $SERVER_MISC_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    public static $SERVER_GANJI_MS_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3499,
    );
    public static $SERVER_GANJI_MS_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3499,
    );
    public static $SERVER_JIAOYOU_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy',
        'password'  => 'Ofd123gjup123',
        'port'      => '3800',
    );
    public static $SERVER_JIAOYOU_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy_r',
        'password'  => 'Ofd123gjup1RO',
        'port'      => '3800',
    );
    public static $SERVER_JIAOYOU_SECONDARY_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy',
        'password'  => 'Ofd123gjup123',
        'port'      => '3800',
    );
    public static $SERVER_JIAOYOU_SECONDARY_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy_r',
        'password'  => 'Ofd123gjup1RO',
        'port'      => '3800',
    );
    public static $SERVER_JIAOYOU_EDM_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy_r',
        'password'  => 'Ofd123gjup1RO',
        'port'      => '3800',
    );
    /**
     * 交友归档库
     * @var array
     */
    public static $SERVER_JIAOYOU_ARCHIVE_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy',
        'password'  => 'Ofd123gjup123',
       'port'      => '3802',
    );
    public static $SERVER_JIAOYOU_ARCHIVE_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy_r',
        'password'  => 'Ofd123gjup1RO',
        'port'      => '3802',
    );

    public static $SERVER_QUEUE = array(
        array(
            'host'      => self::COMMON_HOST,
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => '3309',
        ),
    );
    public static $SERVER_LIVES_QUEUE = array(
        array(
            'host'      => self::COMMON_HOST,
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => '3309',
        ),
    );
    public static $SERVER_JIAOYOU_QUEUE = array(
        array(
            'host'      => self::COMMON_HOST,
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => '3309',
        ),
    );
    public static $SERVER_GUAZI_QUEUE = array(
        array(
            'host'      => self::COMMON_HOST,
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => '3309',
        ),
    );
    public static $SERVER_CRM_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3320',
    );
    public static $SERVER_CRM_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3320',
    );
    public static $SERVER_VEHICLE_PREMIER_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3320',
    );
    public static $SERVER_VEHICLE_PREMIER_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3320',
    );
    /// 帮帮主库
    public static $SERVER_BANG_MASTER   = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    /// 帮帮从库
    public static $SERVER_BANG_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /// 公司信息库主库
    public static $SERVER_CO_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbco',
        'password'  => 'YTAq901234ao',
        'port'      => 3340,
    );
    public static $SERVER_CO_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbco_r',
        'password'  => 'ui12oiql1312',
        'port'      => 3340,
    );


    /// 置顶队列配置
    public static $SERVER_STICKY_QUEUE    = array(
        array(
            'host'      => self::COMMON_HOST,    //sh 10.1.3.19:3309 bj 192.168.116.29:3306
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => 3309,
        )
    );

    /// 公司服务执照更新 到emp的队列配置
    public static $SERVER_EMP_QUEUE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbjy',
        'password'  => 'Ofd123gjup123',
        'port'      => 3309,
    );

    /// resume base主库
    public static $SERVER_RESUME_BASE_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    /// resume base 从库
    public static $SERVER_RESUME_BASE_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /// emp主库
    public static $SERVER_EMP_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3330,
    );
    /// emp 从库
    public static $SERVER_EMP_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3330,
    );

    /// countdown 主库
    public static $SERVER_COUNTDOWN_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3320,
    );
    /// countdown 从库
    public static $SERVER_COUNTDOWN_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3320,
    );

    /**
     * 归档从库
     * @var array
     */
    public static $SERVER_ARCHIVE_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /// bbs主库
    public static $SERVER_BBS_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3320,
    );

    /**
     * PPC主库
     * @var array
     */
    public static $SERVER_PPC_MASTER    = array(
        //'host'      => 'localhost',
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        //'port'      => self::COMMON_PORT,
        'port'      => 3320,
    );

    /**
     * PPC从库
     * @var array
     */
    public static $SERVER_PPC_SLAVE    = array(
        //'host'      => 'localhost',
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        //'port'      => self::COMMON_PORT,
        'port'      => 3320,
    );

    /**
     * CT主库
     * @var array
     */
    public static $SERVER_CT_MASTER    = array(
        'host'      => 'localhost',
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * CT从库
     * @var array
     */
    public static $SERVER_CT_SLAVE    = array(
        'host'      => 'localhost',
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    // 本地机房的 call transfer
    public static $SERVER_CT_LOCAL = array(
        'host'      => '192.168.64.154',
        'username'  => 'dbdev',
        'password'  => 'ganjidev',
        'port'      => '3306',
    );

    /**
     * 楼盘从库
     * @var array
     */
    public static $SERVER_LOUPAN_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 服务主库
     * @var array
     */
    public static $SERVER_FUWU_MASTER   = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 服务从库
     * @var array
     */
    public static $SERVER_FUWU_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 教育店铺帖子统计
     *
     */
    public static $SERVER_JIAOYU_REPORT_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3380',
    );

    public static $SERVER_JIAOYU_REPORT_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3380',
    );


    // developer 只在test1上用
    public static $SERVER_DEVELOPER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    public static $SERVER_REWARD_MASTER = array(
        'host'      => '192.168.112.3',
        'username'  => 'off_dbrw',
        'password'  => 'off_dbrw_5C63EF29',
        'port'      => '3850',
    );
    public static $SERVER_REWARD_SLAVE = array(
        'host'      => '192.168.112.3',
        'username'  => 'off_dbrw_r',
        'password'  => 'off_dbrw_r_615C63EF29',
        'port'      => '3850',
    );
    public static $SERVER_REWARD_QUEUE = array(
        array(
            'host'      => '192.168.112.1',
            'username'  => 'off_dbrw',
            'password'  => 'off_dbrw_5C63EF29',
            'port'      => '3309',
        ),
    );
    public static $WANTED_FINDJOB_MISC_MASTER = array(
         'host'      => self::COMMON_HOST,
         'username'  => self::COMMON_USERNAME,
         'password'  => self::COMMON_PASSWD,
         'port'      => self::COMMON_PORT,
    );
    public static $WANTED_FINDJOB_MISC_SLAVE = array(
         'host'      => self::COMMON_HOST,
         'username'  => self::COMMON_USERNAME,
         'password'  => self::COMMON_PASSWD,
         'port'      => self::COMMON_PORT,
    );
    public static $CRM_QUEUE = array(
         'host'      => 'yz-off-ku-qa00',
         'username'  => self::COMMON_USERNAME,
         'password'  => self::COMMON_PASSWD,
         'port'      => '3309',
    );
    //添加主站队列
    public static $SERVER_MS_QUEUE = array(
            'host'      => '10.3.255.21',
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => '3309',
            );
    /**
     * 用于同步用户身份认证状态的队列库(同步交友、服务)
     */
    public static $SERVER_NEW_QUEUE = array(
        array(
            'host'      => self::COMMON_HOST,
            'username'  => 'off_dbjy',
            'password'  => 'Ofd123gjup123',
            'port'      => '3309',
        ),
    );
    public static $SERVER_HTG_GCRM_QUEUE_MASTER  = array(
        'host'     => '10.3.255.21',
        'username' => self::COMMON_USERNAME,
        'password' => self::COMMON_PASSWD,
        'port'     => 3309,
    );
    public static $SERVER_MASTER_CPC  = array(
        'host'     => self::COMMON_HOST,
        'username' => self::CPC_USERNAME,
        'password'   => self::CPC_PASSWD,
        'port'     => self::CPC_PORT,
    );
    public static $SERVER_SLAVE_CPC  = array(
        'host'     => self::COMMON_HOST,
        'username' => self::CPC_USERNAME,
        'password'   => self::CPC_PASSWD,
        'port'     => self::CPC_PORT,
    );

    /**
     * 自助置顶主库
     * @var array
     */
    public static $SERVER_STICKY_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * 自助置顶从库
     * @var array
     */
    public static $SERVER_STICKY_SLAVE     = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );


    /**
     * 定向推广主库
     * @var array
     */
    public static $SERVER_SELF_DIRECTION_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * 定向推广从库
     * @var array
     */
    public static $SERVER_SELF_DIRECTION_SLAVE     = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * 竞价主库
     * @var array
     */
    public static $SERVER_SELF_BIDDING_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * 竞价从库
     * @var array
     */
    public static $SERVER_SELF_BIDDING_SLAVE     = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * promotion主库
     * @var array
     */
    public static $SERVER_PROMOTION_MASTER    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * promotion从库
     * @var array
     */
    public static $SERVER_PROMOTION_SLAVE     = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',
    );

    /**
     * 联盟广告主库
     * @var array
     */
    public static $SERVER_UNIONAD_MASTER = array();

    public static $SERVER_UNIONAD_SLAVE = array();

    public static $SERVER_SELF_TOP_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',

//        'host'      => '10.3.255.21',
//        'username'  => 'off_ppc_manage',
//        'password'  => 'ppc@2010',
//        'port'      => '3320',
            );

    public static $SERVER_SELF_TOP_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3400',

//        'host'      => '10.3.255.21',
//        'username'  => 'off_ppc_manage',
//        'password'  => 'ppc@2010',
//        'port'      => '3320',
    );
    public static $SERVER_POST_REFRESH_LOG_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3350',
    );

    public static $SERVER_GUAZI_XAPIAN = array(
        array(
            'host'      => self::COMMON_HOST,
            'username'  => 'off_dbse_r',
            'password'  => 'b913eb636',
            'port'      => '3309',
        ),
    );

    public static $SERVER_GANJI_PET = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3310',
    );

    public static $SERVER_MOBILE_MASTER    = array(
        'host'      => '10.3.255.21',
        'username'  => 'off_dbmob',
        'password'  => 'off_dbmob_F1F3617',
        'port'      => 3870,
    );

    public static $SERVER_MOBILE_SLAVE    = array(
        'host'      => '10.3.255.21',
        'username'  => 'off_dbmob',
        'password'  => 'off_dbmob_F1F3617',
        'port'      => 3870,
    );

    // 二手物品 主库
    public static $SERVER_SECONDMARKET_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    // 二手物品 从库
    public static $SERVER_SECONDMARKET_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    // ganji_forum 主库
    public static $SERVER_GANJI_FORUM_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    // ganji_forum 从库
    public static $SERVER_GANJI_FORUM_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    // 租车主库
    public static $SERVER_ZUCHE_MASTER   = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3410,
    );
    // 租车从库
    public static $SERVER_ZUCHE_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3410,
    );



    // 报表服务器 主库
    public static $SERVER_REPORT_MASTER  = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3380',
    );

    // 报表服务器 从库
    public static $SERVER_REPORT_SLAVE  = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3380',
    );

    /**
     * 装修主库
     * @var array
     */
    public static $SERVER_FUWU_ZHUANGXIU_MASTER   = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3410,
    );

    /**
     * 装修从库
     * @var array
     */
    public static $SERVER_FUWU_ZHUANGXIU_SLAVE    = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3410
    );

    /**
     * MOBILE 队列库
     * @var array
     */
     public static $SERVER_QUEUE_MASTER = array(
        'host'  => '192.168.116.29',
        'username' => 'mymobxxx',
        'password' => 'PvIIJVKxj4GvO8cSENxxxx',
        'port'     => '3306',
    );
    /**
     * 短信群发
     * @var array
     */
    public static $SERVER_SMS_BATCH = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3330,
    );
    /**
     * 房产归档库
     * @var array
     */
    public static $SERVER_HOUSING_PIGEONHOLE_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'myfang',
        'password'  => 'd70cdfg0eb6',
        'port'      => 3833,
    );
    public static $SERVER_HOUSING_PIGEONHOLE_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3833,
    );

    const DB_MOBILE_QUEUE = 'mobqe';

    /**
     * 支付向BOSS传队列
     * @var array
     */
    public static $SERVER_UC2BOSS_QUEUE = array(
        'host'      => '10.3.255.21',
        'username'  => 'qibing',
        'password'  => '9o3vVFcjd',
        'port'      => 3309,
    );

    /**
     * 支付库
     * @var array
     */
    public static $SERVER_PAY_MASTER = array(
         'host'      => '10.3.255.21',
         'hostname'  => '10.3.255.21',
         'port'      => 3340,
         'username'  => 'off_dbsrt',
         'password'  => '65c16c8b6',
    );

    /**
     * 支付从库
     * @var array
     */
    public static $SERVER_PAY_SLAVE = array(
         'host'      => '10.3.255.21',
         'hostname'  => '10.3.255.21',
         'port'      => 3340,
         'username'  => 'off_dbsrt',
         'password'  => '65c16c8b6',
    );

    /**
     * 团购从库
     * @var array
     */
    public static $SERVER_TUANGOU_SLAVE = array(
         'host'  => self::COMMON_HOST,
         'username'  => self::COMMON_USERNAME,
         'password'  => self::COMMON_PASSWD,
         'port'      => 3390,
    );

    /**
     * 网络招聘会
     */
    public static $SERVER_JOB_FAIRS_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    public static $SERVER_JOB_FAIRS_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 简历电话号码
     */
    public static $SERVER_PHONE_RESUME_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    public static $SERVER_PHONE_RESUME_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 招聘推广后台
     */
    public static $SERVER_WANTED_PREMIER_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_tuiguang',
        'password'  => '201701576',
        'port'      => 3320,
    );
    public static $SERVER_WANTED_PREMIER_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_tuiguang',
        'password'  => '201701576',
        'port'      => 3320,
    );


    /**
     * 简历活跃日志
     */
    public static $SERVER_JOB_RESUME_LOG = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3350,
    );

    /**
     * real库配置
     */
    public static $SERVER_OFF_KU_REAL = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 防垃圾白名單配置
     */
    public static $AS_MASTER_HOST = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbgrt',
        'password'  => '3102a6a7b',
        'port'      => 3890,
    );
    public static $AS_SLAVE_HOST = array(
        'host'      => self::COMMON_HOST,
        'username'  => 'off_dbgrt',
        'password'  => '3102a6a7b',
        'port'      => 3890,
    );
    /**
     * crm交易中心从库
     */
    public static $SERVER_TRADING_CENTER_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3321,
    );

    // price 主库
    public static $SERVER_PRICE_MASTER  = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3892',
    );

    // price 从库
    public static $SERVER_PRICE_SLAVE  = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => '3892',
    );
    /**
     * 橄榄树
     */
    public static $SERVER_JOB_OLIVE_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    public static $SERVER_JOB_OLIVE_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
    /**
     * 采购系统
     * */
    public static $SERVER_CAIGOU_MASTER = array(
            'host'      => self::COMMON_HOST,
            'username'  => self::COMMON_USERNAME,
            'password'  => self::COMMON_PASSWD,
            'port'      => 3382,
    );

    public static $SERVER_CAIGOU_SLAVE = array(
            'host'      => self::COMMON_HOST,
            'username'  => self::COMMON_USERNAME,
            'password'  => self::COMMON_PASSWD,
            'port'      => 3382,
    );
    
    /**
     * 招聘帮帮后台（原招聘推广后台）主库
     */
    public static $SERVER_WANTED_BACKEND_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3320,
    );
    
    /**
     * 招聘帮帮后台（原招聘推广后台）从库
     */
    public static $SERVER_WANTED_BACKEND_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => 3320,
    );

    /**
     * 数据平台导客户端数据从库
     */
    public static $ANA_MOB_SLAVE = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );

    /**
     * 数据平台导客户端数据主库
     */
    public static $ANA_MOB_MASTER = array(
        'host'      => self::COMMON_HOST,
        'username'  => self::COMMON_USERNAME,
        'password'  => self::COMMON_PASSWD,
        'port'      => self::COMMON_PORT,
    );
}

