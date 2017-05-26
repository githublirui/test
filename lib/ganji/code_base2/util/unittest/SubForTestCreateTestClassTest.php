<?php
    /** {{{
      * for test CreateTestClass
      */
    require_once dirname(__FILE__) . "/CreateTestClass.php";
    class SubForTestCreateTestClass extends CreateTestClass{
        public $_classObject = NULL;
public $_unittestDirName = 'unittest';
public $_isCreateUnittestDir = false;

                /**
     * @brief 
     *
     * @param $classname
     *
     * @returns   
     */
        public function __construct($classname){
            return parent::__construct($classname);
        }
        /**
     * @brief 
     *
     * @param $classname
     *
     * @returns   
     * @mock
     *      SubForTestCreateTestClass::__construct('SubForTestCreateTestClass');
     *      SubForTestCreateTestClass::write2file()<=true;
     *      SubForTestCreateTestClass::createSubclass()<=true;
     *      SubForTestCreateTestClass::createMethodString()<=true;
     *      SubForTestCreateTestClass::getProtectedMethod()<=array();
     * @endMock
     * @assert
     *      () == true;
     * @endAssert
     */
        public function protectedMethod2publicMethod(){
            return parent::protectedMethod2publicMethod();
        }
        /**
     * @brief 
     *
     * @param $methodString
     *
     * @returns   
     *
     * @mock
     *      SubForTestCreateTestClass::__construct('SubForTestCreateTestClass');
     *      SubForTestCreateTestClass::getSubclassFileName()<="/tmp/aa.log";
     * @endMock
     * @assert
     *  ("abc") == true;
     * @endassert
     */
        public function write2file($classString){
            return parent::write2file($classString);
        }
        /**
     * @brief 
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('SubForTestCreateTestClass');
     *  ReflectionClass::getFileName()<="/tmp/abc.class.php";
     *  ReflectionClass::getName()<="SubForTestCreateTestClass";
     *
     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     *  SubForTestCreateTestClass::_classObject = $ReflectionClass;
     *  SubForTestCreateTestClass::_getSubClassName()<="";
     * @endMock
     * @assert
     *  () == "/tmp/unittest/SubForTestCreateTestClass.php";
     * @endAssert
     */
        public function getSubclassFileName(){
            return parent::getSubclassFileName();
        }
        /**
     * @brief 
     *
     * @returns   
     * @mock
     *
     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     * @endMock
     * @assert
     *  () == "SubForTest";
     * @endAssert
     */
        public function _getSubClassName(){
            return parent::_getSubClassName();
        }
        /**
     * @brief 
     *
     * @param $methodString
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('SubForTestCreateTestClass');
     *  ReflectionClass::getFileName()<="/tmp/abc.class.php";
     *  ReflectionClass::getName()<="SubForTestCreateTestClass";

     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     *  SubForTestCreateTestClass::_classObject = $ReflectionClass;
     * @endMock
     * @assert
     *  ('abc') = "abc";
     * @endAssert
     */
        public function createSubclass($methodString){
            return parent::createSubclass($methodString);
        }
        /**
     * @brief 
     *
     * @param $protectedMethods
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('SubForTestCreateTestClass');
     *  ReflectionClass::getParameters()<=array('abc');
     *  ReflectionClass::getName()<='abc';
     *
     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     *  SubForTestCreateTestClass::_getDocComment()<="abc";
     *  SubForTestCreateTestClass::getStringParameters()<=array(
     *      'func' => 'abc',
     *      'argv' => 'abc'
     *      );
     * @endMock
     * @assert
     *  (array($ReflectionClass)) = "public function abc(abc){";
     * @endAssert
     */
        public function createMethodString(Array $protectedMethods){
            return parent::createMethodString($protectedMethods);
        }
        /**
     * @brief 
     *
     * @param $methodObject
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('SubForTestCreateTestClass');
     *  ReflectionClass::getName()<='abc';
     *  ReflectionClass::getDocComment()<='xxxxxxxxxxxabc';
     *
     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     *  SubForTestCreateTestClass::_classObject = $ReflectionClass;
     *  SubForTestCreateTestClass::_getSubClassName() <= "sub";
     * @endMock
     * @assert
     *  ($ReflectionClass) == "xxxxxxxxxxxsubabc";
     * @endAssert
     */
        public function _getDocComment($methodObject){
            return parent::_getDocComment($methodObject);
        }
        /**
     * @brief 
     *
     * @param array $parameters
     *
     * @returns  string 
     * @mock
     *  ReflectionClass::__construct('SubForTestCreateTestClass');
     *  ReflectionClass::getName()<='abc';
     *  ReflectionClass::__toString()<='xxxxxxxxxxxabc';
     *
     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     * @endMock
     * @assert
     *  (array($ReflectionClass)) = "\$abc";
     * @endAssert
     *
     */
        public function getStringParameters(Array $parameters){
            return parent::getStringParameters($parameters);
        }
        /**
     * @brief 
     *
     * @param $methods
     *
     * @returns   
     * @mock
     *  ReflectionClass::__construct('SubForTestCreateTestClass');
     *  ReflectionClass::isProtected()<=true;
     *
     *  SubForTestCreateTestClass::__construct("SubForTestCreateTestClass");
     * @endMock
     * @assert
     *  (array($ReflectionClass)) = $ReflectionClass;
     * @endAssert
     */
        public function getProtectedMethod(Array $methods){
            return parent::getProtectedMethod($methods);
        }

    }//}}}

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-04 at 14:59:28.
 */
class SubForTestCreateTestClassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SubForTestCreateTestClass
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->object = new SubForTestCreateTestClass;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::protectedMethod2publicMethod0
     */
    public function testprotectedMethod2publicMethod0()
    {
                /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(array (  0 => 'write2file',  1 => 'createSubclass',  2 => 'createMethodString',  3 => 'getProtectedMethod',))
            ->getMock();

        $this->object->expects($this->any())
            ->method('write2file')
            ->will($this->returnValue(true));


        $this->object->expects($this->any())
            ->method('createSubclass')
            ->will($this->returnValue(true));


        $this->object->expects($this->any())
            ->method('createMethodString')
            ->will($this->returnValue(true));


        $this->object->expects($this->any())
            ->method('getProtectedMethod')
            ->will($this->returnValue(array()));


        
        $this->assertEquals(
            true,
            $this->object->protectedMethod2publicMethod()
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::write2file0
     */
    public function testwrite2file0()
    {
                /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(array (  0 => 'getSubclassFileName',))
            ->getMock();

        $this->object->expects($this->any())
            ->method('getSubclassFileName')
            ->will($this->returnValue("/tmp/aa.log"));


        
        $this->assertEquals(
            true,
            $this->object->write2file("abc")
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::getSubclassFileName0
     */
    public function testgetSubclassFileName0()
    {
            /**
     * Generated by PHPUnit_SkeletonGenerator
     */
    $mockReflectionClass = $this->getMockBuilder("ReflectionClass")
        ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
        ->setMethods(array (  0 => 'getFileName',  1 => 'getName',))
        ->getMock();

        $mockReflectionClass->expects($this->any())
            ->method('getFileName')
            ->will($this->returnValue("/tmp/abc.class.php"));


        $mockReflectionClass->expects($this->any())
            ->method('getName')
            ->will($this->returnValue("SubForTestCreateTestClass"));

        /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(array (  0 => '_getSubClassName',))
            ->getMock();

        $this->object->expects($this->any())
            ->method('_getSubClassName')
            ->will($this->returnValue(""));

$this->object->_classObject = $mockReflectionClass;

        
        $this->assertEquals(
            "/tmp/unittest/SubForTestCreateTestClass.php",
            $this->object->getSubclassFileName()
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::_getSubClassName0
     */
    public function test_getSubClassName0()
    {
                /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(null)
            ->getMock();

        
        $this->assertEquals(
            "SubForTest",
            $this->object->_getSubClassName()
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::createSubclass0
     */
    public function testcreateSubclass0()
    {
            /**
     * Generated by PHPUnit_SkeletonGenerator
     */
    $mockReflectionClass = $this->getMockBuilder("ReflectionClass")
        ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
        ->setMethods(array (  0 => 'getFileName',  1 => 'getName',))
        ->getMock();

        $mockReflectionClass->expects($this->any())
            ->method('getFileName')
            ->will($this->returnValue("/tmp/abc.class.php"));


        $mockReflectionClass->expects($this->any())
            ->method('getName')
            ->will($this->returnValue("SubForTestCreateTestClass"));

        /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(null)
            ->getMock();
$this->object->_classObject = $mockReflectionClass;

        
        $this->assertContains(
            "abc",
            $this->object->createSubclass('abc')
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::createMethodString0
     */
    public function testcreateMethodString0()
    {
            /**
     * Generated by PHPUnit_SkeletonGenerator
     */
    $mockReflectionClass = $this->getMockBuilder("ReflectionClass")
        ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
        ->setMethods(array (  0 => 'getParameters',  1 => 'getName',))
        ->getMock();

        $mockReflectionClass->expects($this->any())
            ->method('getParameters')
            ->will($this->returnValue(array('abc')));


        $mockReflectionClass->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abc'));

        /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(array (  0 => '_getDocComment',  1 => 'getStringParameters',))
            ->getMock();

        $this->object->expects($this->any())
            ->method('_getDocComment')
            ->will($this->returnValue("abc"));


        $this->object->expects($this->any())
            ->method('getStringParameters')
            ->will($this->returnValue(array('func' => 'abc','argv' => 'abc')));


        
        $this->assertContains(
            "public function abc(abc){",
            $this->object->createMethodString(array($mockReflectionClass))
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::_getDocComment0
     */
    public function test_getDocComment0()
    {
            /**
     * Generated by PHPUnit_SkeletonGenerator
     */
    $mockReflectionClass = $this->getMockBuilder("ReflectionClass")
        ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
        ->setMethods(array (  0 => 'getName',  1 => 'getDocComment',))
        ->getMock();

        $mockReflectionClass->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abc'));


        $mockReflectionClass->expects($this->any())
            ->method('getDocComment')
            ->will($this->returnValue('xxxxxxxxxxxabc'));

        /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(array (  0 => '_getSubClassName',))
            ->getMock();

        $this->object->expects($this->any())
            ->method('_getSubClassName')
            ->will($this->returnValue("sub"));

$this->object->_classObject = $mockReflectionClass;

        
        $this->assertEquals(
            "xxxxxxxxxxxsubabc",
            $this->object->_getDocComment($mockReflectionClass)
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::getStringParameters0
     */
    public function testgetStringParameters0()
    {
            /**
     * Generated by PHPUnit_SkeletonGenerator
     */
    $mockReflectionClass = $this->getMockBuilder("ReflectionClass")
        ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
        ->setMethods(array (  0 => 'getName',  1 => '__toString',))
        ->getMock();

        $mockReflectionClass->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('abc'));


        $mockReflectionClass->expects($this->any())
            ->method('__toString')
            ->will($this->returnValue('xxxxxxxxxxxabc'));

        /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(null)
            ->getMock();

        
        $this->assertContains(
            "\$abc",
            $this->object->getStringParameters(array($mockReflectionClass))
        );

    }

    /**
     * Generated from @assert
     *
     * @covers SubForTestCreateTestClass::getProtectedMethod0
     */
    public function testgetProtectedMethod0()
    {
            /**
     * Generated by PHPUnit_SkeletonGenerator
     */
    $mockReflectionClass = $this->getMockBuilder("ReflectionClass")
        ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
        ->setMethods(array (  0 => 'isProtected',))
        ->getMock();

        $mockReflectionClass->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(true));

        /**
         * Generated by PHPUnit_SkeletonGenerator
         */
        $this->object = $this->getMockBuilder("SubForTestCreateTestClass")
            ->setConstructorArgs(array (  0 => 'SubForTestCreateTestClass',))
            ->setMethods(null)
            ->getMock();

        
        $this->assertContains(
            $mockReflectionClass,
            $this->object->getProtectedMethod(array($mockReflectionClass))
        );

    }
}
