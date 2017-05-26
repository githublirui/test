<?php

require_once 'GanjiUnitTest.class.php';
require_once dirname(__FILE__) . "/../SmartyEngine.class.php";

class SmartyEngineTest  extends PHPUnit_Framework_TestCase{

	protected function setUp(){
        
    }
	
	protected function tearDown(){
        
    }
	
	public function testConstruct()
    {
        define('GANJI_V5', dirname(__FILE__) . '/../../../../ganji_v5');
        $config = array(
            'tmpelate_path'         => GANJI_V5 . '/temlate',
            'template_cpatch'       => GANJI_V5 . '/temlate_c',
            'smarty_plugin_path'    => GANJI_V5 . '/plugin',
            'template_cache_path'   => '',
            'isCached'              => false,
        );
        $tplEngine = new SmartyEngine($config);
        $ok = ($tplEngine instanceof SmartyEngine);
        $this->isTrue($ok);
    }
}
