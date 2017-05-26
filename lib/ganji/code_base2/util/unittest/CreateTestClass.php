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
class CreateTestClass{
    protected $_classObject = null;
    protected $_unittestDirName = "unittest";
    protected $_isCreateUnittestDir = false;
    /* {{{ __construct */
    /**
     * @brief 
     *
     * @param $classname
     *
     * @returns   
     */
    public function __construct($classname){
        $this->_classObject = new ReflectionClass($classname);
    }//}}}
    /* {{{ protectedMethod2publicMethod */
    /**
     * @brief 
     *
     * @param $classname
     *
     * @returns   
     * @mock
     *      CreateTestClass::__construct('CreateTestClass');
     *      CreateTestClass::write2file()<=true;
     *      CreateTestClass::createSubclass()<=true;
     *      CreateTestClass::createMethodString()<=true;
     *      CreateTestClass::getProtectedMethod()<=array();
     * @endMock
     * @assert
     *      () ==
     *          true;
     * @endAssert
     */
    public function protectedMethod2publicMethod(){
        return $this->write2file(
            $this->createSubclass(
                $this->createMethodString(
                    $this->getProtectedMethod(
                        $this->_classObject->getMethods()
                    )
                )
            )
        );
    }//}}}
    /* {{{ write2file */
    /**
     * @brief 
     *
     * @param $methodString
     *
     * @returns   
     *
     * @mock
     *      CreateTestClass::__construct('CreateTestClass');
     *      CreateTestClass::getSubclassFileName()<="/tmp/aa.log";
     * @endMock
     * @assert
     *  ("abc") == true;
     * @endassert
     */
    protected function write2file($classString){
        if (false !== file_put_contents($this->getSubclassFileName(), $classString)) {
            return true;
        }
        return false;
    }//}}}
    /* {{{ getSubclassFileName */
    /**
     * @brief 
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('CreateTestClass');
     *  ReflectionClass::getFileName()<="/tmp/abc.class.php";
     *  ReflectionClass::getName()<="CreateTestClass";
     *
     *  CreateTestClass::__construct("CreateTestClass");
     *  CreateTestClass::_classObject = $ReflectionClass;
     *  CreateTestClass::_getSubClassName()<="";
     * @endMock
     * @assert
     *  () == "/tmp/unittest/CreateTestClass.php";
     * @endAssert
     */
    public function getSubclassFileName(){
        $fileName = substr($this->_classObject->getFileName(), 0, -4);//-4 为.php的长度 
        $dir = dirname($fileName);
        $len = strlen($this->_unittestDirName);
        if ( $this->_unittestDirName !== substr($dir,-$len) ) {
            $this->_isCreateUnittestDir = true;
            $dir .= "/".$this->_unittestDirName;
        }
        $className = $this->_classObject->getName();
        return "{$dir}/" . $this->_getSubClassName() . "{$className}.php";
    }//}}}
    /* {{{ _getSubClassName */
    /**
     * @brief 
     *
     * @returns   
     * @mock
     *
     *  CreateTestClass::__construct("CreateTestClass");
     * @endMock
     * @assert
     *  () == "SubForTest";
     * @endAssert
     */
    protected function _getSubClassName(){
        return "SubForTest";
    }//}}}
    /* {{{ createSubclass */
    /**
     * @brief 
     *
     * @param $methodString
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('CreateTestClass');
     *  ReflectionClass::getFileName()<="/tmp/abc.class.php";
     *  ReflectionClass::getName()<="CreateTestClass";

     *  CreateTestClass::__construct("CreateTestClass");
     *  CreateTestClass::_classObject = $ReflectionClass;
     * @endMock
     * @assert
     *  ('abc') = "abc";
     * @endAssert
     */
    protected function createSubclass($methodString){
        //{{{ class string template
        $classContentTemplate = <<<EOF
<?php
/**
  * for test %s
  */
require dirname(__FILE__) . "%s";
class %s%s extends %s{
    %s
}
EOF;
//}}}
        $this->getSubclassFileName();
        $filename = strrchr($this->_classObject->getFileName(), "/");
        if ( true === $this->_isCreateUnittestDir) {
            $requireStr = "/.." . $filename;
        } else {
            $requireStr = $filename; 
        }
        $className = $this->_classObject->getName();
        return sprintf($classContentTemplate,
            $className,
            $requireStr,
            $this->_getSubClassName(),
            $className,
            $className,
            $methodString
        );
    }//}}}
    /* {{{ protected createMethodString */
    /**
     * @brief 
     *
     * @param $protectedMethods
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('CreateTestClass');
     *  ReflectionClass::getParameters()<=array('abc');
     *  ReflectionClass::getName()<='abc';
     *
     *  CreateTestClass::__construct("CreateTestClass");
     *  CreateTestClass::_getDocComment()<="abc";
     *  CreateTestClass::getStringParameters()<=array(
     *      'func' => 'abc',
     *      'argv' => 'abc'
     *      );
     * @endMock
     * @assert
     *  (array($ReflectionClass)) = "public function abc(abc){";
     * @endAssert
     */
    protected function createMethodString(Array $protectedMethods = array()){
        $methodString = "";
        foreach ($protectedMethods as $methodObject) {
            $string = <<<EOF
    %s
    public function %s(%s){
        return parent::%s(%s);
    }

EOF;
            $args = $this->getStringParameters($methodObject->getParameters());
            $methodString .= sprintf($string,
                    $this->_getDocComment($methodObject),
                    $methodObject->getName(),
                    $args['func'],
                    $methodObject->getName(),
                    $args['argv']
            );
        }
        return $methodString;
    }//}}}
    /* {{{ _getDocComment */
    /**
     * @brief 
     *
     * @param $methodObject
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('CreateTestClass');
     *  ReflectionClass::getName()<='abc';
     *  ReflectionClass::getDocComment()<='xxxxxxxxxxxabc';
     *
     *  CreateTestClass::__construct("CreateTestClass");
     *  CreateTestClass::_classObject = $ReflectionClass;
     *  CreateTestClass::_getSubClassName() <= "sub";
     * @endMock
     * @assert
     *  ($ReflectionClass) == "xxxxxxxxxxxsubabc";
     * @endAssert
     */
    protected function _getDocComment($methodObject){
        return str_replace(
                $this->_classObject->getName(),
                $this->_getSubClassName() . $this->_classObject->getName(),
                $methodObject->getDocComment()
        );
    }//}}}
    /* {{{ getStringParameters */
    /**
     * @brief 
     *
     * @param array $parameters
     *
     * @returns  string 
     * @mock
     *  ReflectionClass::__construct('CreateTestClass');
     *  ReflectionClass::getName()<='abc';
     *  ReflectionClass::__toString()<='xxxxxxxxxxxabc';
     *
     *  CreateTestClass::__construct("CreateTestClass");
     * @endMock
     * @assert
     *  (array($ReflectionClass)) = "\$abc";
     * @endAssert
     *
     */
    protected function getStringParameters(Array $parameters){
        $args['func'] = '';
        $args['argv'] = '';
        foreach ($parameters as $object) {
            $type = "";
            $argsStringArray = explode(" ", $object->__toString()); 
            if (7 === count($argsStringArray)) {
                $type = ucwords($argsStringArray[4]);
            }
            
            $args['func'] .= trim("{$type} \$" . $object->getName() . ",");
            $args['argv'] .= trim("\$" . $object->getName() . ",");
        }
        $args['func'] = substr($args['func'], 0, -1);
        $args['argv'] = substr($args['argv'], 0, -1);
        return $args;
    }//}}}
    /* {{{ getProtectedMethod */
    /**
     * @brief 
     *
     * @param $methods
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('CreateTestClass');
     *  ReflectionClass::isProtected()<=true;
     *
     *  CreateTestClass::__construct("CreateTestClass");
     * @endMock
     * @assert
     *  (array($ReflectionClass)) = $ReflectionClass;
     * @endAssert
     */
    protected function getProtectedMethod(Array $methods){
        $protectedMethods = array();
        foreach ($methods as $k => $methodObject) {
            if(true === $methodObject->isProtected()) {
                $protectedMethods[] = $methodObject;
            }
        }
        return $protectedMethods;
    }//}}}
}
