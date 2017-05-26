<?PHP 
/** 
 * @author Dujian,duxiang
 */ 
ob_start();
include_once dirname(__FILE__) . '/../SessionNamespace.class.php'; 
 
class SessionNamespaceTest extends PHPUnit_Framework_TestCase 
{ 
    private $key, $value;

    protected function setUp()
    {
        $this->key = 'phpunittest';
        $this->value = 'phpunittestvalue';
    }

    /** tearDown
     */
    protected function tearDown(){
    	ob_start();
	    ob_end_flush();
    }
    /**
     * @ test setValue and getValue
     */
	public function testSetValue()
    {
        SessionNamespace::setValue($this->key, $this->value);
        $this->assertEquals($this->value, SessionNamespace::getValue($this->key));
    }
    
    /**
     * @ test flashData
     */
    public function testFalshData()
    {
        SessionNamespace::flashData($this->key, $this->value);
        $this->assertEquals($this->value, SessionNamespace::flashData($this->key));

    }

    /**
     * @ test isExists
     */
    public function testIsExists()
    {
        $this->assertEquals(false, SessionNamespace::isExists($this->key));
        SessionNamespace::setValue($this->key, $this->value);
        $this->assertEquals(true, SessionNamespace::isExists($this->key));
    }

    /**
     * @ test delete
     */
    public function testDelete()
    {
        SessionNamespace::setValue($this->key, $this->value);
        $this->assertEquals(true, SessionNamespace::isExists($this->key));
        SessionNamespace::delete($this->key);
        $this->assertEquals(false, SessionNamespace::isExists($this->key));
    }
    
    /**
     * @ test clear
     */
    public function testClear()
    {
        SessionNamespace::setValue($this->key, $this->value);
        $this->assertEquals(true, SessionNamespace::isExists($this->key));
        SessionNamespace::clear();
        $this->assertEquals(false, SessionNamespace::isExists($this->key));
    }
} 
