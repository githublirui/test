<?PHP

/**
 * @author Caifeng
 */
require_once dirname(__FILE__) . '/../../../config/config.inc.php';
//require_once 'PHPUnit/Framework.php';
include_once dirname(__FILE__) . '/../TelCodeA.class.php';

class TelCodeATest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->inst = new TelCodeA();
    }

    public function testEncode() {
        $a = $this->inst->Encode('13601284773');
        $b = $this->inst->Decode($a);
        $this->assertEquals('13601284773', $b);
    }

}
