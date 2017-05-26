<?PHP
require_once( dirname(__FILE__) . "/../../../config/config.inc.php");
require_once( dirname(__FILE__) . "/../../log/Logger.class.php");

//class GlobalConfig
//{
//    public static $MAIL_SERVER = array(
//        "http://192.168.113.20:8123/WebGate/Email.aspx",
//    );
//}

include_once dirname(__FILE__) . '/../MailNamespace.class.php';

class MailTest extends PHPUnit_Framework_TestCase
{
	public function setUp() 
	{
	}
	
	public function testSend()
	{
//        $this->assertTrue( MailNamespace::sendMail(
//                     "tailorcai@gmail.com",
//                     "unittest",
//                     "unittest-content",
//                     MailNamespace::$KEY_MS_GETPASSWORD
//                                ));
	}
}
