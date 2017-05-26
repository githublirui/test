<?php
/**
 * 快速创建mock对象
 * @package              code_base2
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class GanjiMock extends PHPUnit_Framework_TestCase
{
    /* {{{ [费] get */
    /**
     * @brief 获取mock对象，由于有bug，建议使用mockMethod
     *
     * @param $class array
     * @param $method array
     *
     * @returns   
     * 
     * @code
     *  $class  => array(
     *      'name' => string,
     *      'args' => array
     *  )
     *  根据调用次数，返回return_val1,return_val2,...
     *  $method => array(
     *      'method_name' => array(
     *              return_val1,
     *              return_val2,
     *              ...
     *          ),
     *      ...
     *  )
     *  无论怎么调用都返回value
     *  $method => array(
     *      'method_name' => value,
     *      ...
     *  )
     * @endcode
     */
    public function get($class, $method){
        if (is_array($method)) {
            $mock = $this->getMockBuilder($class['name'])
                ->setConstructorArgs($class['args'])
                ->setMethods(array_keys($method))
                ->getMock();
            foreach ($method as $method_name => $returnValues) {
                if (is_array($returnValues)
                    && isset($returnValues['return'])
                    && is_array($returnValues['return'])
                ) {
                    foreach ($returnValues['return'] as $at => $r) {
                        $mock->expects($this->at($at))
                            ->method($method_name)
                            ->will($this->returnValue($r));
                    }
                } else {
                    $mock->expects($this->any())
                        ->method($method_name)
                        ->will($this->returnValue($returnValues));
                }
            }
            return $mock;
        }
        throw new Exception("method 参数错误");
    }//}}}
    /* {{{ mockMethod */
    /**
     * @brief 获得一个mock好的对象
     *
     * @param $class array
     * @param $method array
     *
     * @returns   
     * 
     * @code
     *  $class  => array(
     *      'name' => string,
     *      'args' => array
     *  )
     *  根据调用次数，返回return_val1,return_val2,...
     *  $method => array(
     *      array(
     *          'method' => 'name',
     *          'args'   => array(
     *              '>'    => 0,//参数1大于0
     *              '=' => "xxx",//参数2字符串包含xxx
     *              '*' => null,//参数3可以是任意值
     *          ),//参数1
     *          'return' => 'val1'
     *      ),
     *      array(
     *          'method' => 'name',
     *          'args'   => array(
     *              '>'    => 0,//参数1大于0
     *              '=' => "xxx",//参数2字符串包含xxx
     *              '*' => null,//参数3可以是任意值
     *          ),//参数1
     *          'return' => 'val2'
     *      ),
     *      ...
     *  )
     *  例如：
     *  <?php
     *      $res1 = $db->getAll($sql);//db方法的第一次调用
     *      $res2 = $db->getOne($sql);//db方法的第二次调用
     *      $res1 = $db->getAll($sql);//db方法的第三次调用
     *      //上面mock的method写法如下
     *      $method = array(
     *          0 => array('method' => 'getAll', 'return' => 'val1'),
     *          1 => array('method' => 'getOne', 'return' => 'val2'),
     *          2 => array('method' => 'getAll', 'return' => 'val3'),
     *      );
     *  ?>
     *  无论怎么调用都返回value,不检查参数
     *  $method => array(
     *      'method_name' => value,//
     *      ),
     *      ...
     *  )
     *  无论怎么调用都返回value,检查参数
     *  $method => array(
     *      'method_name' => array(
     *          'return' => value,
     *          'args'   => array(
     *              '>'    => 0,//参数1大于0
     *              '=' => "xxx",//参数2字符串包含xxx
     *              '*' => null,//参数3可以是任意值
     *          ),//参数1
     *      ),
     *      ...
     *  )
     * @endcode
     */
    public function mockMethod($class, $method){
        if (is_array($method)) {
            $mock = $this->createMockBuilder($class, $method);
            foreach ($method as $at => $info) {
                if (is_numeric($at)) {
                    if (isset($info['args']) && is_array($info['args'])) {//分析参数检查部分

                        $args = $this->getArgsString($info['args']);
                        $obj = $mock->expects($this->at($at))
                            ->method($info['method']);
                        $cmd = "\$obj = \$obj->with($args);";
                        eval($cmd);
                        $obj->will($this->returnValue($info['return']));
                    } else {
                        $mock->expects($this->at($at))
                            ->method($info['method'])
                            ->will($this->returnValue($info['return']));
                    }
                } else {
                    if (is_array($info)
                        && isset($info['args'])
                        && is_array($info['args'])
                    ) {//分析参数检查部分

                        $args = $this->getArgsString($info['args']);
                        $obj = $mock->expects($this->any())
                            ->method($at);
                        $cmd = "\$obj = \$obj->with($args);";
                        eval($cmd);
                        $obj->will($this->returnValue($info['return']));
                    } else {
                        $mock->expects($this->any())
                            ->method($at)
                            ->will($this->returnValue($info));
                    }
                }
            }
            return $mock;
        }
        throw new Exception("method 参数错误");
    }//}}}
    /* {{{ protected createMockBuilder */
    /**
     * @brief 获取mock对象
     *
     * @param $class
     *
     * @returns  MockObject 
     * @codeCoverageIgnore
     */
    protected function createMockBuilder($class, $method){
        $m = array();
        foreach ($method as $at => $info) {
            if (is_numeric($at)) {
                $m[] = $info['method'];
            } else {
                $m[] = $at;
            }
        }
        $m = array_unique($m); 
        if (isset($class['args'])) {
            $mock = $this->getMockBuilder($class['name'])
                ->setConstructorArgs($class['args'])
                ->setMethods($m)
                ->getMock();
        } else {
            $mock = $this->getMockBuilder($class['name'])
                ->setMethods($m)
                ->getMock();
        }
        return $mock;
    }//}}}
    /* {{{ protected getArgsString */
    /**
     * @brief 参数检查字符串
     *
     * @param array $methodArgs 参数检查数组
     *
     * @returns  String 
     * @codeCoverageIgnore
     */
    protected function getArgsString($methodArgs){
        $args = "";
        foreach ($methodArgs as $k => $value) {
            switch($k){
                case ">":
                    $args .= "\$this->greaterThan({$value}),";
                    break;
                case "=":
                    $args .= "\$this->stringContains(\"{$value}\"),";
                    break;
                case "*":
                default:
                    $args .= "\$this->anything(),";
                    break;
            }
        }
        $args = trim($args, ",");
        return $args;
    }//}}}
}
