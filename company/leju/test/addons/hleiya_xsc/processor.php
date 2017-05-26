<?php
/**
 * 向上城模块处理程序
 *
 * @author hleiya
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hleiya_xscModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$content = $this->message['content'];
		$setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
		
		$from = $this->message['from'];
		$url = $this->createMobileurl('index',array('dopenid'=>$from));
		$news[] = array(
			'title' =>$setting['title'],
			'description' =>$setting['sub_title'],
			'picurl' => $setting['contact_img'],
			'url' =>$url
		);
		//return $this->respText($from);	
		return $this->respNews($news);	
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
}