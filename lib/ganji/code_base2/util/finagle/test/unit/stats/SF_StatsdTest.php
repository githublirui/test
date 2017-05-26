<?php
/**
 * Created by PhpStorm.
 * User: zhaoweiguo
 * Date: 14-5-12
 * Time: PM4:57
 * @brief 测试statsd功能是否正常
 *
 */


require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once CODE_BASE2 . '/util/profile/statsd.php';

/**
 * Class SF_StatsdTest
 * @brief 主要用于测试StatsD功能正确性
 */
class SF_StatsdTest extends PHPUnit_Framework_TestCase {


    protected function setUp() {
        $GLOBALS['STATSD_HOST'] = "192.168.115.108";    // 设定stats测试服务器
        $GLOBALS['STATSD_PORT'] = "8125";
    }

    public function testGetCount() {
        $num = 20000;
        for($i=0; $i<=$num; $i++) {
            StatsD::get()->counting('finagle.php.test.statstest1.total', 1, 1);
            StatsD::get()->timing('finagle.php.test.statstest1', 500, 1);

            StatsD::get()->counting('finagle.php.test.statstest2.total', 1, 0.01);
            StatsD::get()->timing('finagle.php.test.statstest2', 500, 0.01);

            StatsD::get()->counting('finagle.php.test.statstest3.total', 1, 1);
            StatsD::get()->timing('finagle.php.test.statstest3', rand(0, 500), 1);
            usleep(1000*10);  // 0.1s
        }
    }
/*
    public function testGetTiming() {
        for($i=0; $i<20000; $i++) {
            StatsD::get()->timing('finagle.php.test.test1', 500, 0.1);
            StatsD::get()->timing('finagle.php.test.test2', 500, 0.2);

            StatsD::get()->timing('finagle.php.test.test3', 500, 0.5);
            StatsD::get()->timing('finagle.php.test.test4', 500, 1);
            usleep(10000);  // 0.01s
        }
    }
*/

}