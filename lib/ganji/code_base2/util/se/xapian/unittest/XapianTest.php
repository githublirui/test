<?PHP
/**
 * Xapian test case
 * 
 * 
 */
require_once dirname(__FILE__).'/../Xapian.class.php';

class XapianTest extends PHPUnit_Framework_TestCase
{
    private $xapian;
    
    public function setUp()
    {
        /* comment out by tailor
        $this->xapian = new Xapian("127.0.0.1", 1000);
        */
    }
    public function atestQuery()
    {
        $count = 0;
        //判断北京二手物品->家具 列表页数据肯定大于1条数据(http://bj.ganji.com/jiaju/)
        $queryString = '{}{[F:category:1]&[F:major_category:5]&[F:city:0]&[F:deal_type:0]&
            [F:agent:0]&[N:listing_status:5:50]}{[QF:<id,title,price,deal_type,major_category,
            minor_category,minor_category_name,minor_category_url,agent,refresh_at,post_at,
            district_id,district_name,street_id,street_name,thumb_img,listing_status,post_type>]
            [S:listing_status:DESC][S:post_at:DESC][L:0:50]}';
        $result = $this->xapian->query($queryString);
        $count = $result['count'];
        $this->assertGreaterThanOrEqual(1, $count);
    }
    public function atestSearchCount()
    {
        //判定(http://bj.ganji.com/wu/s/_%E5%AE%B6%E5%85%B7)是否有值
        $queryString ='{[T:TT:家具]}{[F:category:1]&[F:city:0]&[N:listing_status:5:100]}
        {[QF:<id,major_category,title,description,district_id,street_id,post_at,listing_status,post_type>]
        [S:listing_status:DESC][S:post_at:DESC][L:0:20]}';
        $result = $this->xapian->searchCount($queryString);
        if(is_array($result)){
            foreach ($result as $k=>&$v) {
                if (0 == $v) {
                    unset($v);
                }
            }
        }
        $count = count($result);
        $this->assertGreaterThanOrEqual(1, $count);
    }
    public function tearDown()
    {
        unset($this->xapian);
    }
}