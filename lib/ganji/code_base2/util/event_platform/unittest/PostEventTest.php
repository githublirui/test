<?PHP 
/** 
 * @author Dujian
 */ 
$GLOBALS['CODEBASE_ROOT'] = dirname(__FILE__) . '/../../../';
include_once $GLOBALS['CODEBASE_ROOT'] . '/config/config.inc.php'; 
include_once dirname(__FILE__) . '/../PostEvent.class.php'; 

class GanjiConfig {
    public static $event_proxy = array(
        'host'  => '192.168.113.71', // search1
        'port'  => 20333,
    );
}

class PostEventTest extends PHPUnit_Framework_TestCase
{
    private $data;

    protected function setUp()
    {
        //创建一个帖子
        // todo 

        //设置初始值
        $this->data = array(
            'city' => 100,
            'category' => 4,
            'major_category' => 16,
            'post_id' => 2082716,
        );
    }

    public function testCreateTriggerPost()
    {
        PostEvent::createTriggerPost($this->data);
    }

    public function testUpdateTriggerPost()
    {
        PostEvent::updateTriggerPost($this->data);
    }

    public function testDeleteTriggerPost()
    {
        PostEvent::deleteTriggerPost($this->data);
    }
}

