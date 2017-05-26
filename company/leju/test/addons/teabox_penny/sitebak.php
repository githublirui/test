<?php
/**
 * 茶盒一分钱模块微站定义
 *
 * @author teabox.cc
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Teabox_pennyModuleSite extends WeModuleSite {	
    
    public function doMobilePay() {
		global $_W,$_GPC;

        $uid=$_GPC['teabox_penny_uid'.$_W['uniacid']];

        if(!$uid){
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&do=auth&m=teabox_penny");
            exit;
        }

        $user = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );
        if(!$user){
            isetcookie('teabox_penny_uid'.$_W['uniacid'], null);
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&do=auth&m=teabox_penny");
            exit;
        }

        $setting = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
            
        $this->initSetting($setting);

        require_once '../addons/teabox_penny/pay/WxPay.Api.php';
        require_once '../addons/teabox_penny/pay/WxPay.JsApiPay.php';

        $url =$setting['pay_url'].'?bburl='.urlencode($_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=pay&m=teabox_penny');

        $tools = new JsApiPay();
        $openId = $tools->GetOpenid($url);

        if(empty($openId)){
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&do=pay&m=teabox_penny");
            exit;
        }

        $out_trade_no=date("YmdHis").'_'.$_W['uniacid'].'_'.$uid;//WxPayConfig::MCHID.date("YmdHis");
        
        if($setting['pay_fee']<=0) $setting['pay_fee']=0.01;

        $input = new WxPayUnifiedOrder();
        $input->SetBody($setting['main_title']);
        $input->SetAttach("");
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee(intval($setting['pay_fee']*100));
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("");
        $input->SetNotify_url($_W['siteroot'].'addons/teabox_penny/pay/notify.php');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);
        
        $wxConfig=$this->getJssdkConfig();

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('teabox_penny_pay')." WHERE uniacid=".$_W['uniacid']);

		$payinfo = pdo_fetch('SELECT sort FROM ' . tablename('teabox_penny_pay') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ORDER BY `dateline` DESC', array(':uniacid' => $_W['uniacid'],':uid' => $uid ) );

		$payinfo['sort']=$payinfo['sort']?$payinfo['sort']:0;

        include $this->template('pay');
	}


    public function doMobileAuth() {
        global $_W,$_GPC;
        $setting = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

        if(empty($_GPC['code'])){
            $back_url =$setting['pay_url'].'?bburl='.urlencode($_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=auth&m=teabox_penny');

            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$setting['pay_appid']."&redirect_uri=".urlencode($back_url)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";

            header("location:".$url);
            exit;
        }

        $param=array();
        $param ['appid']=$setting['pay_appid'];
        $param ['secret'] = $setting['pay_appsecret'];
        $param ['code'] = $_GPC['code'];
        $param ['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query ( $param );
        $content = file_get_contents ( $url );
        $content = json_decode ( $content, true );        

        $param=array();
        $param ['access_token'] = $content ['access_token'];
        $param ['openid'] = $content ['openid'];
        $param ['lang'] = 'zh_CN';
        $url = 'https://api.weixin.qq.com/sns/userinfo?' . http_build_query ( $param );
        $content = file_get_contents ( $url );
        $wxuser = json_decode ( $content, true );
        
        $user = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_user') . ' WHERE `uniacid` = :uniacid AND `openid` = :openid', array(':uniacid' => $_W['uniacid'],':openid' => $wxuser['openid'] ) );
        
        $data=array(
            'uniacid'=>$_W['uniacid'],
            'nickname'=>$wxuser['nickname'],
            'headimgurl'=>$wxuser['headimgurl'],
            'openid'=>$wxuser['openid']
        );
        if($user){
            pdo_update('teabox_penny_user', $data, array('id' => $user['id']));
        }else{
            pdo_insert('teabox_penny_user', $data);
            $user['id']=pdo_insertid();
        }
        isetcookie('teabox_penny_uid'.$_W['uniacid'], $user['id'],86400*7);

        header("location: ./index.php?i=".$_W['uniacid']."&c=entry&do=pay&m=teabox_penny");
        exit;
    }

    public function doMobileShare() {
        global $_W,$_GPC;
        
        $uid=$_GPC['teabox_penny_uid'.$_W['uniacid']];

        $user = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('teabox_penny_pay')." WHERE uniacid=".$_W['uniacid']);

        $setting = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

		$this->initSetting($setting);

        require_once '../addons/teabox_penny/pay/WxPay.Api.php';
        require_once '../addons/teabox_penny/pay/WxPay.JsApiPay.php';

        $url =$setting['pay_url'].'?bburl='.urlencode($_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=share&m=teabox_penny');

        $tools = new JsApiPay();
        $openId = $tools->GetOpenid($url);

        if(empty($openId)){
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&do=auth&m=teabox_penny");
            exit;
        }

        $out_trade_no=date("YmdHis").'_'.$_W['uniacid'].'_'.$uid;//WxPayConfig::MCHID.date("YmdHis");
        
        if($setting['pay_fee']<=0) $setting['pay_fee']=0.01;

        $input = new WxPayUnifiedOrder();
        $input->SetBody($setting['main_title']);
        $input->SetAttach("");
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee(intval($setting['pay_fee']*100));
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("");
        $input->SetNotify_url($_W['siteroot'].'addons/teabox_penny/pay/notify.php');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);

		$payinfo = pdo_fetch('SELECT sort FROM ' . tablename('teabox_penny_pay') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ORDER BY `dateline` DESC', array(':uniacid' => $_W['uniacid'],':uid' => $uid ) );

		$payinfo['sort']=$payinfo['sort']?$payinfo['sort']:0;

        $wxConfig=$this->getJssdkConfig();

        include $this->template('share');
    }

	public function doWebSetting() {
		global $_W,$_GPC;

        $setting = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        
        if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting'){
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'logo'=>$_GPC['logo'],
				'title'=>$_GPC['title'],
                'main_title'=>$_GPC['main_title'],
				'sub_title'=>$_GPC['sub_title'],
				'pay_btn_title'=>$_GPC['pay_btn_title'],
				'pay_over_title'=>$_GPC['pay_over_title'],
				'pay_over_content'=>$_GPC['pay_over_content'],
				'contact_img'=>$_GPC['contact_img'],
				'share_img'=>$_GPC['share_img'],
				'share_title'=>$_GPC['share_title'],
				'share_desc'=>$_GPC['share_desc'],
                'share_banner'=>$_GPC['share_banner'],
            );
            if($setting){
                pdo_update('teabox_penny_setting', $data, array('id' => $setting['id']));
                $id=$setting['id'];
            }else{
                pdo_insert('teabox_penny_setting', $data);
                $id=pdo_insertid();
            }
            echo $id;
			exit;
		}
        load()->func('tpl');
		include $this->template('setting');
	}
	public function doWebPaysetting() {
		global $_W,$_GPC;
		$setting = pdo_fetch('SELECT * FROM ' . tablename('teabox_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        
        if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'paysetting'){
            $data=array(
				'uniacid'=>$_W['uniacid'],
                'pay_appid'=>$_GPC['pay_appid'],
                'pay_mchid'=>$_GPC['pay_mchid'],
                'pay_key'=>$_GPC['pay_key'],
                'pay_appsecret'=>$_GPC['pay_appsecret'],
                'pay_fee'=>$_GPC['pay_fee'],
                'pay_url'=>$_GPC['pay_url']
            );
            if($setting){
                pdo_update('teabox_penny_setting', $data, array('id' => $setting['id']));
                $id=$setting['id'];
            }else{
                pdo_insert('teabox_penny_setting', $data);
                $id=pdo_insertid();
            }
            echo $id;
			exit;
		}
        load()->func('tpl');
		include $this->template('pay_setting');
	}
	public function doWebPaylist() {
		global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 50;
        
        $eid=$_GPC['eid'];
        $sort=$_GPC['sort'];
        $condition = "a.uniacid=".$_W['uniacid'];
        if($sort){
            $condition .= " AND a.sort=".$sort;
        }

        
        $sql = "SELECT a.sort,a.total_fee,a.dateline,b.nickname,b.headimgurl FROM ".tablename('teabox_penny_pay') . " a LEFT JOIN " . tablename('teabox_penny_user') . " b ON a.uid=b.id WHERE $condition ORDER BY a.dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql);
        
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('teabox_penny_pay')." a WHERE $condition");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('pay_list');
	}

    public function doWebClearpay() {
        global $_W;
        pdo_delete('teabox_penny_pay', array('uniacid' => $_W['uniacid']));
        echo 1;
    }

    private function initSetting($setting) {
        define('WXPAY_APPID', $setting['pay_appid']);
        define('WXPAY_MCHID', $setting['pay_mchid']);
        define('WXPAY_KEY', $setting['pay_key']);
        define('WXPAY_APPSECRET', $setting['pay_appsecret']);
        define('WXPAY_PAY_URL', $setting['pay_url']);
    }

    private function getJssdkConfig(){
        global $_W;
        $jsapiTicket = $_W['account']['jsapi_ticket']['ticket'];
        $nonceStr = $this->createNonceStr();
		$timestamp = TIMESTAMP;
		$url = $_W['siteurl'];
		$string1 = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
		$signature = sha1($string1);
		$config = array(
			"appId"		=> $_W['account']['key'],
			"nonceStr"	=> $nonceStr,
			"timestamp" => "$timestamp",
			"signature" => $signature,
		);
        return $config;
    }

    private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}


}