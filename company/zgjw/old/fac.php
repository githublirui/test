<?php
/**
 * 定义运算类
 */
abstract class Operation {

    protected $_NumberA = 0;
    protected $_NumberB = 0;
    protected $_Result = 0;

    public function __construct($A, $B) {
        $this->_NumberA = $A;
        $this->_NumberB = $B;
    }

    public function setNumber($A, $B) {
        $this->_NumberA = $A;
        $this->_NumberB = $B;
    }

    /*
      protected function clearResult(){
      $this->_Result = 0;
      }
     */

    public function clearResult() {
        $this->_Result = 0;
    }

//抽象方法无方法体
    abstract protected function getResult();
}

//继承一个抽象类的时候，子类必须实现抽象类中的所有抽象方法；
//另外，这些方法的可见性 必须和抽象类中一样（或者更为宽松）
class OperationAdd extends Operation {

    public function getResult() {
        $this->_Result = $this->_NumberA + $this->_NumberB;
        return $this->_Result;
    }

}

class OperationSub extends Operation {

    public function getResult() {
        $this->_Result = $this->_NumberA - $this->_NumberB;
        return $this->_Result;
    }

}

class OperationMul extends Operation {

    public function getResult() {
        $this->_Result = $this->_NumberA * $this->_NumberB;
        return $this->_Result;
    }

}

class OperationDiv extends Operation {

    public function getResult() {
        $this->_Result = $this->_NumberA / $this->_NumberB;
        return $this->_Result;
    }

}

class OperationFactory {

//创建保存实例的静态成员变量
    private static $obj;

//创建访问实例的公共的静态方法
    public static function CreateOperation($type, $A, $B) {
        switch ($type) {
            case '+':
                self::$obj = new OperationAdd($A, $B);
                break;
            case '-':
                self::$obj = new OperationSub($A, $B);
                break;
            case '*':
                self::$obj = new OperationMul($A, $B);
                break;
            case '/':
                self::$obj = new OperationDiv($A, $B);
                break;
        }
        return self::$obj;
    }

}

$obj = OperationFactory::CreateOperation('+');
$obj->setNumber(4,4);
$obj = OperationFactory::CreateOperation('*', 5, 6);
echo $obj->getResult();
echo '<br>';
$obj->clearResult();
echo '<br>';
echo $obj->_Result;