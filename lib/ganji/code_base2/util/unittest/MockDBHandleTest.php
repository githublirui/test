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
class MockDBHandleTest extends MockDBHandle
{
    /* {{{ testGet */
    /**
     * @brief 
     *
     * @returns   
     */
    public function testGet(){
        $method = array(
            'getOne' => array(
                'args' => array("1", '2'),
                'return' => array('r', 'r2')
            ),
            'execute' => array(
                'args' => array("2"),
                'return' => array(2=>'r2')
            )
        );
        $mock = $this->get($method);
        $res1 = $mock->getOne('1');
        $this->assertEquals($res1, 'r');
        $res1 = $mock->getOne('1');
        $this->assertEquals($res1, 'r2');
        $res2 = $mock->execute('2');
        $this->assertEquals($res2, 'r2');
    }//}}}
    /* {{{ testMockMethod */
    /**
     * @brief 
     *
     * @returns   
     */
    public function testMockMethod(){
        $method = array(
            array(
                'method' => 'getOne',
                'sql' => "1",
                'return' => 'r'
            ),
            array(
                'method' => 'execute',
                'sql' => "2",
                'return' => 'r2'
            )
        );
        $mock = $this->mockMethod($method);
        $res1 = $mock->getOne('1');
        $res2 = $mock->execute('2');
    }//}}}
}

