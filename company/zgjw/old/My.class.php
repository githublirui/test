<?php

include './Utils.class.php';

/**
 * 产品认证企业名录采取
 * 表结构
  CREATE TABLE `ent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) DEFAULT NULL COMMENT '企业名称',
  `province` varchar(255) DEFAULT NULL COMMENT '省市名称',
  `product_category` varchar(255) DEFAULT NULL COMMENT '产品种类',
  `approve_time` date DEFAULT NULL COMMENT '批准时间',
  `expiry_date` date DEFAULT NULL COMMENT '有效期限',
  `certificate_number` varchar(255) DEFAULT NULL COMMENT '证书编号',
  `tested_products` text COMMENT '认证产品',
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
 * 
 */
class EntCollecter {

    /**
     * 采集内容并存入数据库 
     */
    public static function run() {
        $link = DbManager::dbConnect();
        set_time_limit(0);
        $i = 1;
        do {
            //匹配内容
            $url = "http://datacenter.sepacec.com/entSearch.do?method=view&id=" . $i;
            $content = @file_get_contents($url);
            preg_match_all('/<td\s*[^>]+bgcolor=\"\#FFFFFF\"[^>]+>\s*[\&nbsp\;]*(.*?)\s*<\/td>/is', $content, $match);
            $company_name = self::processStrForMysql(@$match[1][0]);
            $province = self::processStrForMysql(@$match[1][1]);
            $product_category = self::processStrForMysql(@$match[1][2]);
            $approve_time = self::processStrForMysql(@$match[1][3]);
            $expiry_date = self::processStrForMysql(@$match[1][4]);
            $certificate_number = self::processStrForMysql(@$match[1][5]);
            $tested_products = self::processStrForMysql(@$match[1][6]);

            //执行sql
            if (count($match[1]) > 0) {
                //判断日期是否正确并且是否在设置的分类内
                //if (strlen($approve_time) == 10 && strlen($expiry_date) == 10 && in_array($product_category, $allow_product_categorys)) {
                if (strlen($approve_time) == 10 && strlen($expiry_date) == 10) {
                    $sql = 'replace into ent(company_name,province,product_category,approve_time,expiry_date,certificate_number,tested_products) 
            values("' . $company_name . '","' . $province . '","' . $product_category . '","' . $approve_time . '","' . $expiry_date . '","' . $certificate_number . '","' . $tested_products . '")';
                    mysql_query($sql);

                    //debug
                    //file_put_contents("c:/test.txt", '"' . $company_name . '", "' . $province . '", "' . $product_category . '", "' . $approve_time . '", "' . $expiry_date . '", "' . $certificate_number . '", "' . $tested_products . '"' . "\n", FILE_APPEND);
                    echo '.';
                    flush();
                }
            }

            $i++;
        } while ($i < 8700); //id 到8700结束，中间可能有的id删除，所以全部循环

        mysql_close($link);
    }

    private static function processStrForMysql($str) {
        return mysql_real_escape_string(@iconv('gb2312', 'utf-8', trim($str)));
    }

}

class DbManager {

    private static $server = 'localhost';
    private static $username = 'root';
    private static $password = '';
    private static $db = 'cn_collect';

    /**
     * 数据库连接 
     */
    public static function dbConnect() {
        $link = mysql_connect(self::$server, self::$username, self::$password) or die('connect error');
        mysql_select_db(self::$db, $link) or die('select db error');
        mysql_query("SET NAMES UTF8");
        return $link;
    }

    public static function setServer($server) {
        self::$server = $server;
    }

    public static function setUername($username) {
        self::$server = $username;
    }

    public static function setPassword($password) {
        self::$server = $password;
    }

    public static function setDb($db) {
        self::$server = $db;
    }

}

function alert($msg) {
    
}

?>
