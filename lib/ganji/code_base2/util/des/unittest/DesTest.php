<?php 

require_once dirname(__FILE__)  . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/util/des/DesNamespace.class.php';

class DesTest extends PHPUnit_Framework_TestCase {

    public function testDes() {
        // 为了保证java的兼容性必须是8位
        $key = '12a,.5BC';
        $encrypt = DesNamespace::create(DesNamespace::MODE_DES, $key);
        
        $test = 13810555202;
        $encryptStr = $encrypt->encrypt($test);
        $decryptStr = $encrypt->decrypt($encryptStr);
//        var_dump($encryptStr, $decryptStr);exit();
        $decryptStr = $encrypt->decrypt($encryptStr);

        $this->assertEquals($decryptStr, $test);
    }

    public function testDes3() {
        // 8的倍数
        $key = '12a,.5BC22223333';
        $iv  = "s(2L@f!a";
        $encrypt = DesNamespace::create(DesNamespace::MODE_3DES, $key, $iv);
        
        $test = 13810555202;
        $encryptStr = $encrypt->encrypt($test);
        $decryptStr = $encrypt->decrypt($encryptStr);
//        $decryptStr = $encrypt->decrypt('hI4siFrmyiPrCkGcSwyTyA==');
//        var_dump($decryptStr);exit();
//        var_dump($encryptStr, $decryptStr);exit();

        $this->assertEquals($decryptStr, $test);
    }

    public function testAes() {
        // 为了保证java的兼容性必须是8位
        $key = '1ddd2a,.5BC12121233a';
        $encrypt = DesNamespace::create(DesNamespace::MODE_AES, $key);

        $test = 13810555202;
        $encryptStr = $encrypt->encrypt($test);
        $decryptStr = $encrypt->decrypt($encryptStr);
//var_dump($encryptStr, $decryptStr);exit();
        $this->assertEquals($decryptStr, $test);
    }
    
    protected function setUp() {
    }

    protected function tearDown() {
    }
}