<?php
/**
 * 茶盒一分钱模块处理程序
 *
 * @author teabox.cc
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Teabox_pennyModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;

        $setting = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

		$from = $this->message['from'];
		$url = $this->createMobileurl('pay',array('dopenid'=>$from));
		$news[] = array(
			'title' =>$setting['title'],
			'description' =>$setting['sub_title'],
			'picurl' => $setting['contact_img'],
			'url' =>$url
		);
		//return $this->respText($from);	
		return $this->respNews($news);	
	}
}