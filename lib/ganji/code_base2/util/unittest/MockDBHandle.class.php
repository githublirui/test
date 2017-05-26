<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
require_once dirname(__FILE__) . "/GanjiMock.class.php";
class MockDBHandle extends PHPUnit_Framework_TestCase
{
    /* {{{ get */
    /**
     * @brief 获得DBHandle的mock对象
     *
     * @param $method array
     *
     * @returns   
     * 
     * @code
     *  $method => array(
     *      'method_name' => array(
     *              'sql' => array(
     *                  index0 => sql1,
     *                  index1 => sql2,
     *                  ...
     *              ),
     *              'return' => array(
     *                  index0 => value1,
     *                  index1 => value2,
     *                  ...
     *              ),
     *          ),
     *      ...
     *  )
     *
     * @endcode
     */
    public function get($method){
        if (is_array($method)) {
            $class = array(
                'name' => 'DBHandle',
                'args' => array(null)
            );
            foreach ($method as $k => &$value) {
                if (!isset($value['args']) && isset($value['sql'])) {
                    $value['args'] = array("=" => $value['sql']);
                }
            }
            $mock = new GanjiMock();
            return $mock->get($class, $method);
        }
        throw new Exception("method 参数错误");
    }//}}}
    /* {{{ mockMethod */
    /**
     * @brief 根据method调用顺序来获得mock对象
     *
     * @param $method
     *
     * @returns  MockObject 
     *  $method => array(
     *          index0 => array(
     *              'method' => ...,
     *              'sql' => ...,
     *              'return' => ...
     *          ),
     *          index1 => array(
     *              'method' => ...,
     *              'sql' => ...,
     *              'return' => ...
     *          ),
     *          ...
     *          ),
     *      ...
     *  )
     */
    public function mockMethod($method){
        if (is_array($method)) {
            $class = array(
                'name' => 'DBHandle',
                'args' => array(null)
            );
            foreach ($method as $k => &$value) {
                if (!isset($value['args']) && isset($value['sql'])) {
                    $value['args'] = array("=" => $value['sql']);
                }
            }
            $mock = new GanjiMock();
            return $mock->mockMethod($class, $method);
        }
        throw new Exception("method 参数错误");
    }//}}}
}
