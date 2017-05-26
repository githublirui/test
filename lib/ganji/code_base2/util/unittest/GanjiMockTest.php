<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   yangyu <yangyu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2013, www.ganji.com
 */
require_once dirname(__FILE__) . "/../db/DBHandle.class.php";
require_once dirname(__FILE__) . "/MockDBHandle.class.php";
require_once dirname(__FILE__) . "/GanjiMock.class.php";
class GanjiMockTest extends GanjiMock
{
    /* {{{ testMockMethod */
    /**
     * @brief 
     *
     * @returns   
     */
    public function testMockMethod(){
        $class = array(
            'name' => 'DBHandle',
            'args' => array(null)
        );
        $method = array(
            array(
                'method' => 'getOne',
                'return' => 'r'
            ),
            array(
                'method' => 'execute',
                'return' => 'r2'
            ),
            array(
                'method' => 'getAll',
                'args'   => array(
                    "=" => "xxx"
                ),
                'return' => 'r3'
            ),
            array(
                'method' => 'getAll',
                'args'   => array(
                    "=" => "xxxx"
                ),
                'return' => 'r4'
            )
        );
        $mock = $this->mockMethod($class, $method);
        $res1 = $mock->getOne('1');
        $this->assertEquals($res1, "r");
        $res2 = $mock->execute('2');
        $this->assertEquals($res2, "r2");
        $res3 = $mock->getAll('xxx');
        $this->assertEquals($res3, "r3");
        $res = $mock->getAll('xxxx');
        $this->assertEquals($res, "r4");
    }//}}}
}

