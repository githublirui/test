<?php
/**
 * ����������
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

//���󷽷��޷�����
    abstract protected function getResult();
}

//�̳�һ���������ʱ���������ʵ�ֳ������е����г��󷽷���
//���⣬��Щ�����Ŀɼ��� ����ͳ�������һ�������߸�Ϊ���ɣ�
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

//��������ʵ���ľ�̬��Ա����
    private static $obj;

//��������ʵ���Ĺ����ľ�̬����
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