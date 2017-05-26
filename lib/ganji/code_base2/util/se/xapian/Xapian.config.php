<?PHP
/**
 * XapianSearchEngine HOST PORT 配置
 */
//线上环境
class XapianSearchEngine
{
	const HOST    = "192.168.113.73"; 
	const PORT_1  = 26200; //二手物品
	const PORT_2  = 26200; //宠物 
	const FUWU_HOST  = "192.168.113.71";//大服务 
    const FUWU_PORT  = 26200; //大服务 
    const DETAIL_PAGE_HOST = "";
    const DETAIL_PAGE_PORT = "26210";
	
	 /**
     * xapian port映射表
     * 
     */
    public  static $xapianServerMap = array(
        'house_source_rent'             =>XapianSearchEngine::PORT_1,
        'xiaoqu_bus_list'               =>XapianSearchEngine::PORT_1,
        'house_source_rent_premier'     =>XapianSearchEngine::PORT_1,
        'house_source_wantrent'         =>XapianSearchEngine::PORT_1,
        'house_source_share'            =>XapianSearchEngine::PORT_1,
        'house_source_share_premier'    =>XapianSearchEngine::PORT_1,
        'house_source_shortrent'        =>XapianSearchEngine::PORT_1,
        'house_source_sell'             =>XapianSearchEngine::PORT_1,
        'house_source_sell_premier'     =>XapianSearchEngine::PORT_1,
        'house_source_wantbuy'          =>XapianSearchEngine::PORT_1,
        'house_source_officerent'       =>XapianSearchEngine::PORT_1,
        'house_source_officerent_premier' =>XapianSearchEngine::PORT_1,
        'house_source_officetrade'      =>XapianSearchEngine::PORT_1,
        'house_source_officetrade_premier'      =>XapianSearchEngine::PORT_1,
        'house_source_storerent'        =>XapianSearchEngine::PORT_1,
        'house_source_storerent_premier'        =>XapianSearchEngine::PORT_1,
        'house_source_storetrade'       =>XapianSearchEngine::PORT_1,
        'house_source_storetrade_premier'       =>XapianSearchEngine::PORT_1,
        'house_source_plant'            =>XapianSearchEngine::PORT_1,
        'findjob_post'                  =>XapianSearchEngine::PORT_1,
        'secondmarket_post'             =>XapianSearchEngine::PORT_1,
        'pet_post'                      =>XapianSearchEngine::PORT_1,
        'wanted_post'                   =>XapianSearchEngine::PORT_1,
        'parttime_wanted_post'          =>XapianSearchEngine::PORT_1,
        'training_post'                 =>XapianSearchEngine::PORT_1,
        'vehicle_post'                  =>XapianSearchEngine::PORT_1,
        'vehicle_source'                =>XapianSearchEngine::PORT_1,
        'service_biz_post'              =>XapianSearchEngine::PORT_1,
        'service_living_post'           =>XapianSearchEngine::PORT_1,
        'service_shop_post'             =>XapianSearchEngine::PORT_1,
        'ticketing_post'                =>XapianSearchEngine::PORT_1,
        'ticket_post'                   =>XapianSearchEngine::PORT_1,
        'personals_post'                =>XapianSearchEngine::PORT_1,
        'event_post'                    =>XapianSearchEngine::PORT_1,
        'all_threads'                   =>XapianSearchEngine::PORT_1,
    );

}
//test环境
class XapianSearchEngine_TEST
{
    const HOST    = "192.168.113.88";
    const VEHICLE_HOST    = "192.168.112.35"; 
    const PORT_1  = 26200; //二手物品
    const PORT_2  = 26200; //宠物
    const FUWU_HOST  = "192.168.112.35";//大服务 
    const FUWU_PORT  = 26200; //大服务 
    const DETAIL_PAGE_HOST = "192.168.113.88";
    const DETAIL_PAGE_PORT = "26210";

}
