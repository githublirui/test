<?php
/**
 * Created by PhpStorm.
 * User: zhaoweiguo
 * Date: 14-4-22
 * Time: PM2:52
 */
require_once dirname(__FILE__) . "/../../../SF_Bootstrap.php";


require_once FINAGLE_BASE."/exception/SF_Exception.php";


class SF_ExceptionTest extends PHPUnit_Framework_TestCase {


    public function testHttpStatus() {
        try {
           $exp = new SF_HttpInvalidException("test");
           throw $exp;

        } catch(SF_HttpInvalidException $e) {
            echo $e->getMessage();
            $this->assertTrue(true);
        } catch(SF_Exception $e) {
            $this->assertTrue(false);
        }
    }

}