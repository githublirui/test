<?php
/**
 * 以下配置来自v3，为了解决并存期间DBConfig命名冲突的问题，统一挪到V5中，在v5中，严禁使用以下的配置
 * @author zhoufan
 */
class DBConfigV3 {
    /**
     * 数据类类别选择
     *
     */
    const MASTER = 0;
    const SLAVE = 1;
    const DB10 = 2;
    const Queue = 3;
    const CRM = 4;
    const TRADING_CENTER = 5;
    const SITE_SEARCH = 6;
    const MANAGEMENT = 7;
    const GANJI_BBS = 8;
    const TEST_BBS = 9;
    const ARCHIVE = 10;
    const MISC = 11;
    const GANJI_LOG = 12;
    const GANJI_FANG_LOG = 13;
    const BANGBANG = 14;
    const BANGBANG_SLAVE = 15;
    const MANAGEMENT_MASTER = 16;
    const VEHICLE_PREMIER = 17;
    const VEHICLE_PREMIER_SLAVE = 18;
    const CRM_SLAVE = 26;
    const PPC = 22;
    const PPC_SLAVE = 23;
    const CALL_TRANSFER_MASTER = 24;
    const CALL_TRANSFER_SLAVE = 25;
    const PPC_SITE = 27;
    const PPC_SITE_SLAVE = 28;
    const HOUSING_PREMIER = 30;
    const HOUSING_PREMIER_SLAVE = 31;
    const SITE_REPORTING = 32;
    const SLOW_SEARCH_DB = 33;
    const TRADING_CENTER_SLAVE = 34;
    const USER_CENTER_SLAVE = 35;
    const BUSINESS_ACCOUNT = 36;
    const BUSINESS_ACCOUNT_SLAVE = 37;
    const SELF_TOP = 38;
    const SELF_TOP_SLAVE = 39;
    const WANTED_PREMIER = 40;
    const WANTED_PREMIER_SLAVE = 41;
    const GROUPON = 42;
    const GROUPON_SLAVE = 43;
    const MS_SLOW_DB_SLAVE = 45;

    const SLAVE_REAL = 99;
    const MASTER_CPC = 100;
    const SLAVE_CPC = 101;

    const MASTER_EMP = 102;
    const SLAVE_EMP = 103;

    const MASTER_RESUME_BASE = 104;
    const SLAVE_RESUME_BASE = 105;

    const HTG_QUEUE_MASTER = 110;
    const HTG_INTERFACE_QUEUE_MASTER = 111;
    const HTG_GCRM_QUEUE_MASTER = 112;
    const IMAGE_SIMILAR_SLAVE = 120;
    const HOUSING_PREMIER_ARCH_MASTER = 121;
    const HOUSING_PREMIER_ARCH_SLAVE = 122;
    const SERVER_HOUSING_PIGEONHOLE_MASTER = 123;

    /**
     * 数据库编码标识
     *
     */
    const ENCODING_GBK = 0;
    const ENCODING_UTF8 = 1;
    const ENCODING_LATIN = 2;

    /**
     * 数据库名称
     */
    const CRM_DATABASE_NAME = "gcrm";
    const VEHICLE_PREMIER_DATABASE_NAME = "vehicle_premier";
    const HOUSING_PREMIER_DATABASE_NAME = "house_premier";
    const WANTED_PREMIER_DATABASE_NAME = "wanted_premier";
    const REPORTING_DATABASE_NAME = "house_report";
    const PPC_DATABASE_NAME = "ppc";
    const PPC_SITE_DATABASE_NAME = "ppc_site";
    const CALL_DATABASE_NAME = "ct";
    const BUSINESS_ACCOUNT_DATABASE_NAME = 'biz_acc';
    const SELF_TOP_DATABASE_NAME = 'biz_top';
    const GROUPON_DATABASE_NAME = 'groupon';
    const MANAGEMENT_DATABASE_NAME = "management";

    // 通用的一些常量
    const COMMON_HOST     = '10.3.255.21'; //yz-off-ku-qa00
    //const COMMON_HOST     = '192.168.129.21'; //zgc-off-ku-qa
    const COMMON_USERNAME = 'off_dbsrt';
    const COMMON_PASSWD = '65c16c8b6';
    const COMMON_PORT = '3310';

    // 报表DB设置
    const REPORT_USERNAME = 'off_tg_web_r';
    const REPORT_PASSWD = '8cc0674a2';
    const REPORT_PORT = 3380;
    //cpc
    const CPC_USERNAME = 'off_dbcpc';
    const CPC_PASSWD = '816f6a9ab';
    const CPC_PORT = 3810;


    /**
     * 数据库配置数组
     * DB_TYPE => array(
     *    'host'     => "localhost",  // host address
     *    'username' => "dbdev",          // database user name
     *    'passwd'   => "ganjidev",       // database password
     *    'port'     => "3306",           // database port default 3306
     *    'hostName' => "site_master",    // 用于标示DB类型，例如：主站主库
     *    'monitorDatabase' => "beijing"，// 用于监控的数据库名称
     *  )
     */
    static $DBConfig = array(
            self::MASTER  => array(
                'host'     => self::COMMON_HOST,
                'userName' => self::COMMON_USERNAME,
                'passwd'   => self::COMMON_PASSWD,
                'port'     => self::COMMON_PORT,
                'hostName' => "site_master",
                'monitorDatabase' => 'beijing'
                ),
            self::MASTER_CPC  => array(
                'host'     => self::COMMON_HOST,
                'userName' => self::CPC_USERNAME,
                'passwd'   => self::CPC_PASSWD,
                'port'     => self::CPC_PORT,
                'hostName' => "site_master",
                'monitorDatabase' => 'house_cpc'
                ),
            self::SLAVE_CPC  => array(
                'host'     => self::COMMON_HOST,
                'userName' => self::CPC_USERNAME,
                'passwd'   => self::CPC_PASSWD,
                'port'     => self::CPC_PORT,
                'hostName' => "site_master",
                'monitorDatabase' => 'house_cpc'
                ),

            self::SLAVE   => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "site_slave",
                    'monitorDatabase' => 'beijing'
                    ),
            self::SLAVE_REAL   => array(
                    'host'     => array("192.168.116.20"),
                    'userName' => 'shenlijun',//'zhoufan',
                    'passwd'   => 'decf58577',//'6a938c670',
                    'port'     => 3310,
                    'hostName' => "site_slave",
                    'monitorDatabase' => 'beijing'
                    ),
            self::MS_SLOW_DB_SLAVE   => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "site_slave",
                    'monitorDatabase' => 'beijing'
                    ),
            self::DB10    => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "db10",
                    'monitorDatabase' => 'beijing'
                    ),
            self::Queue  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3350',
                    'hostName' => "queue",
                    'monitorDatabase' => 'queue'
                    ),
            self::CRM    => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_tuiguang',
                    'passwd'   => '201701576',
                    'port'     => '3320',
                    'hostName' => "crm",
                    'monitorDatabase' => 'gcrm'
                    ),
            self::CRM_SLAVE    => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_tuiguang',
                    'passwd'   => '201701576',
                    'port'     => '3320',
                    'hostName' => "crm",
                    'monitorDatabase' => 'gcrm'
                    ),
            self::TRADING_CENTER  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbucsrt',
                    'passwd'   => 'e7db83587',
                    'port'     => '3340',
                    'hostName' => "trading_center",
                    'monitorDatabase' => 'trading_center'
                    ),
            self::SITE_SEARCH  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "site_search",
                    'monitorDatabase' => 'beijing'
                    ),
            self::MANAGEMENT  => array(
                    'host'     => '10.3.255.21',
                    'userName' => 'off_mymsc_r',
                    'passwd'   => 'JHAS129sioq1',
                    'port'     => '3311',
                    'hostName' => "management_slave",
                    'monitorDatabase' => 'management'
                    ),
            self::GANJI_BBS  => array(
                    'host'     => 'db8',
                    'userName' => 'ganjibbs',
                    'passwd'   => 'ganji1111BbS',
                    'port'     => '3306',
                    'hostName' => "ganji_bbs",
                    'monitorDatabase' => 'bbsv3'
                    ),
            self::TEST_BBS  => array(
                    'host'     => 'db8',
                    'userName' => 'ganjibbs',
                    'passwd'   => 'ganji1111BbS',
                    'port'     => '3306',
                    'hostName' => "test_bbs",
                    'monitorDatabase' => 'bbsv3'
                    ),
            self::ARCHIVE   => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "archive",
                    'monitorDatabase' => 'archive'
                    ),
            self::MISC      => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "misc",
                    'monitorDatabase' => 'ganji_misc'
                    ),
            self::GANJI_LOG => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "ganji_log",
                    'monitorDatabase' => ''
                    ),
            self::GANJI_FANG_LOG => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "ganji_fang_log",
                    'monitorDatabase' => 'fang_log'
                    ),
            self::BANGBANG    => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "bangbang_master",
                    'monitorDatabase' => 'bang'
                    ),
            self::BANGBANG_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "bangbang_slave",
                    'monitorDatabase' => 'bang'
                    ),
            self::MANAGEMENT_MASTER => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_mymsc',
                    'passwd'   => 'hpctiEepEIty',
                    'port'     => '3311',
                    'hostName' => "management_master",
                    'monitorDatabase' => 'management'
                    ),
            self::VEHICLE_PREMIER => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3320',
                    'hostName' => "vehicle_premier",
                    'monitorDatabase' => 'vehicle_premier'
                    ),
            self::VEHICLE_PREMIER_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3320',
                    'hostName' => "vehicle_premier",
                    'monitorDatabase' => 'vehicle_premier'
                    ),
            self::HOUSING_PREMIER => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3328',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::HOUSING_PREMIER_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3328',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::HOUSING_PREMIER_ARCH_MASTER => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3833',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::HOUSING_PREMIER_ARCH_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3833',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::PPC => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_ppc_sys_w',
                    'passwd'   => 'saj1203KJDAS',
                    'port'     => '3320',
                    'hostName' => 'ppc',
                    'monitorDatabase' => 'ppc'
                    ),
            self::PPC_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_ppc_ana_r',
                    'passwd'   => 'sAS7621jXsad',
                    'port'     => '3320',
                    'hostName' => 'ppc_slave',
                    'monitorDatabase' => 'ppc_slave'
                    ),
            self::PPC_SITE  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "ppc_site_master",
                    'monitorDatabase' => 'ppc_site'
                    ),
            self::PPC_SITE_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "ppc_site_slave",
                    'monitorDatabase' => 'ppc_site'
                    ),
            self::CALL_TRANSFER_MASTER => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_ct_sys_w',
                    'passwd'   => 'SD2198JKDHkjsa9',
                    'port'     => '3320',
                    'hostName' => 'call_transfer_master',
                    'monitorDatabase' => 'call_transfer_master'
                    ),
            self::CALL_TRANSFER_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_ct_ana_r',
                    'passwd'   => 'D2DHkX892XAksa9',
                    'port'     => '3320',
                    'hostName' => 'call_transfer_slave',
                    'monitorDatabase' => 'call_transfer_slave'
                    ),
            // 报表设置
            self::SITE_REPORTING  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::REPORT_USERNAME,
                    'passwd'   => self::REPORT_PASSWD,
                    'port'     => '3380',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::SLOW_SEARCH_DB   => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "site_slave",
                    'monitorDatabase' => 'beijing'
                    ),
            self::TRADING_CENTER_SLAVE  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbucsrt',
                    'passwd'   => 'e7db83587',
                    'port'     => '3340',
                    'hostName' => "trading_center",
                    'monitorDatabase' => 'trading_center'
                    ),
            self::USER_CENTER_SLAVE  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbucsrt',
                    'passwd'   => 'e7db83587',
                    'port'     => '3340',
                    'hostName' => "trading_center",
                    'monitorDatabase' => 'trading_center'
                    ),
            self::BUSINESS_ACCOUNT => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_ppc_manage',
                    'passwd'   => 'ppc@2010',
                    'port'     => '3320',
                    'hostName' => "business_account",
                    'monitorDatabase' => 'business_account'
                    ),
            self::BUSINESS_ACCOUNT_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_ppc_manage',
                    'passwd'   => 'ppc@2010',
                    'port'     => '3320',
                    'hostName' => "business_account_slave",
                    'monitorDatabase' => 'business_account'
                    ),
            self::SELF_TOP => array(
                    'host'     => '10.3.255.21',
                    'userName' => 'off_dbsrt',
                    'passwd'   => '65c16c8b6',
                    'port'     => '3400',
                    
//                    'host'     => self::COMMON_HOST,
//                    'userName' => 'off_ppc_manage',
//                    'passwd'   => 'ppc@2010',
//                    'port'     => '3320',
                    'hostName' => "self_top",
                    'monitorDatabase' => 'self_top'
                    ),
            self::SELF_TOP_SLAVE => array(
                    'host'     => '10.3.255.21',
                    'userName' => 'off_dbsrt',
                    'passwd'   => '65c16c8b6',
                    'port'     => '3400',
            
//                    'host'     => self::COMMON_HOST,
//                    'userName' => 'off_ppc_manage',
//                    'passwd'   => 'ppc@2010',
//                    'port'     => '3320',
                    'hostName' => "self_top_slave",
                    'monitorDatabase' => 'self_top'
                    ),
            self::WANTED_PREMIER    => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_tuiguang',
                    'passwd'   => '201701576',
                    'port'     => '3320',
                    'hostName' => "crm",
                    'monitorDatabase' => 'gcrm'
                    ),
            self::WANTED_PREMIER_SLAVE    => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_tuiguang',
                    'passwd'   => '201701576',
                    'port'     => '3320',
                    'hostName' => "crm",
                    'monitorDatabase' => 'gcrm'
                    ),
            self::GROUPON   => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbgo',
                    'passwd'   => 'GOread1234',
                    'port'     => '3390',
                    'hostName' => "groupon",
                    'monitorDatabase' => 'groupon'
                    ),
            self::GROUPON_SLAVE   => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbgo',
                    'passwd'   => 'GOread1234',
                    'port'     => '3390',
                    'hostName' => "groupon",
                    'monitorDatabase' => 'groupon'
                    ),
            //临时
            300  => array(
                    'host'     => '192.168.113.233',
                    'userName' => 'zhangjiafa',
                    'passwd'   => '0fdf80785',
                    'port'     => '3310',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::MASTER_EMP  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbsrt',
                    'passwd'   => '65c16c8b6',
                    'port'     => 3330
                    ),
            self::SLAVE_EMP  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbsrt',
                    'passwd'   => '65c16c8b6',
                    'port'     => 3330
                    ),
            self::MASTER_RESUME_BASE  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "",
                    'monitorDatabase' => ''
                    ),
            self::SLAVE_RESUME_BASE  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => self::COMMON_PORT,
                    'hostName' => "",
                    'monitorDatabase' => ''
                    ),
            self::HTG_QUEUE_MASTER  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbtg',
                    'passwd'   => 'off_dbtg_D73583664D',
                    'port'     => 3309,
                    'hostName' => "site_master",
                    'monitorDatabase' => 'tgqe'
                    ),
            self::HTG_INTERFACE_QUEUE_MASTER  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => 'off_dbsrt',
                    'passwd'   => '65c16c8b6',
                    'port'     => 3309,
                    ),
            self::IMAGE_SIMILAR_SLAVE => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3320',
                    'hostName' => '',
                    'monitorDatabase' => ''
                    ),
            self::HTG_GCRM_QUEUE_MASTER  => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => 3309,
                    ),
            self::SERVER_HOUSING_PIGEONHOLE_MASTER => array(
                    'host'     => self::COMMON_HOST,
                    'userName' => self::COMMON_USERNAME,
                    'passwd'   => self::COMMON_PASSWD,
                    'port'     => '3833',
                    ),
            );
}
