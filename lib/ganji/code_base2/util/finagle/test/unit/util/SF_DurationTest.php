<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tailor
 * Date: 14-3-28
 * Time: 下午5:23
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__) . "/../SF_Duration.php";

class SF_DurationTest extends  PHPUnit_Framework_TestCase {
    public function testConvert() {
        $dur = SF_Duration::fromMilliseconds( 15000 );
        $this->assertEquals( $dur->inMicroseconds(), 15000*1000);
        $this->assertEquals( $dur->inSeconds(), 15);

        // exceeds 32bit limit
        $dur = SF_Duration::fromSeconds(5000);
        $this->assertEquals( $dur->inMicroseconds(), 5000*1000*1000);
    }

}
