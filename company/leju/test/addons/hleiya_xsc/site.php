<?php
/**
 * 向上城模块微站定义
 *
 * @author hleiya
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hleiya_xscModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W,$_GPC;
		$dopenid = $_GPC['dopenid'];
		
		$setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid'] ));
		
        $this->initSetting($setting);
		//$wxConfig=$this->getJssdkConfig();
	
		
        $uid=$_GPC['hleiya_xsc_uid'.$_W['uniacid']];

		
        if(!$uid){
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&dopenid=$dopenid&do=auth&m=hleiya_xsc");
            exit;
        }

        $user = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );
		$userpay = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_repay') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid ) );
        if(!$user){
            isetcookie('hleiya_xsc_uid'.$_W['uniacid'], null);
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&dopenid=$dopenid&do=auth&m=hleiya_xsc");
            exit;
        }
		

        require_once '../addons/hleiya_xsc/pay/WxPay.Api.php';
        require_once '../addons/hleiya_xsc/pay/WxPay.JsApiPay.php';

        $url ="http://www.hfwxz.com/app/index.php?i=".$_W['uniacid']."&c=entry&do=auth&dopenid=$dopenid&m=hleiya_xsc";
		
        $tools = new JsApiPay();

        $openId = $tools->GetOpenid($url);
				
        if(empty($openId)){
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&dopenid=$dopenid&do=index&m=hleiya_xsc");
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
        $input->SetNotify_url($_W['siteroot'].'addons/hleiya_xsc/pay/notify.php');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);

        $order = WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);
        
        
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_pay')." WHERE uniacid=".$_W['uniacid']);
		$num = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_user')." WHERE uniacid=".$_W['uniacid']);
		$num = $num+NUM;
		$payinfo = pdo_fetch('SELECT sort FROM ' . tablename('hleiya_penny_pay') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ORDER BY `dateline` DESC', array(':uniacid' => $_W['uniacid'],':uid' => $uid ) );

		$payinfo['sort']=$payinfo['sort']?$payinfo['sort']:0;
		
		if (!$user['dopenid']){
			if($dopenid){

				$d=array(
					'dopenid'=>$dopenid
				);
				pdo_update('hleiya_penny_user', $d, array('id' => $user['id']));
			}else{
				include $this->template('index2');
				exit;
			}
			
		}

        if($user['money'] && $user['ispay']=='1'){
			
			$total_fee = $user['money']/100;
            include $this->template('reg3');
			exit;
        }
		include $this->template('index');
	}

	public function doMobileAjax() {
		global $_W,$_GPC;
        $uid=$_GPC['hleiya_xsc_uid'.$_W['uniacid']];
		$time = strtotime(date('Y-m-d'));

		$d=array(
            'ispay'=>'1',
        );
        pdo_update('hleiya_penny_user', $d, array('id' => $uid));
		$result=array('error'=>'0','msg'=>'恭喜您');
		echo json_encode($result);exit;
	}
	public function doMobileReg() {
		global $_W,$_GPC;
		$uid=$_GPC['hleiya_xsc_uid'.$_W['uniacid']];
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_pay')." WHERE uniacid=".$_W['uniacid']);
		$num = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_user')." WHERE uniacid=".$_W['uniacid']);
		$num = $num+NUM;
		$setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid'] ));
		$dopenid = $_GPC['dopenid'];
		$user = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );
		include $this->template('reg');
	}
	public function doMobileReg2() {
		global $_W,$_GPC;
		$uid=$_GPC['hleiya_xsc_uid'.$_W['uniacid']];
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_pay')." WHERE uniacid=".$_W['uniacid']);
		$num = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_user')." WHERE uniacid=".$_W['uniacid']);
		$num = $num+NUM;
		$setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid'] ));
		$dopenid = $_GPC['dopenid'];
		$user = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );
		if($user['cid']){
			$idstr = explode(',',$user['cid']);
		}
		
		
		include $this->template('reg2');
	}
	public function doMobileReg3() {
		global $_W,$_GPC;
		$uid=$_GPC['hleiya_xsc_uid'.$_W['uniacid']];
		$idstr = explode(',',$_GPC['cid']);

		$user = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );
		if($user['money']!=''){
			$total_fee = $user['money']/100;
		}else{
			$num = count($idstr);
			if($num=='1'){
				$fee = rand(100,110);
			}elseif($num=='2'){
				$fee = rand(110,120);
			}elseif($num=='3'){
				$fee = rand(130,140);
			}elseif($num=='4'){
				$fee = rand(150,160);
			}else{
				echo "请选择";exit;
			}
			$total_fee = $fee/100;
			$d=array(
				'cid'=>$_GPC['cid'],
				'money'=>$fee
			);
			pdo_update('hleiya_penny_user', $d, array('id' => $uid));
		}
		
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_pay')." WHERE uniacid=".$_W['uniacid']);
		$num = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_user')." WHERE uniacid=".$_W['uniacid']);
		$num = $num+NUM;
		$setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid'] ));
		$dopenid = $_GPC['dopenid'];
		$user = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_user') . ' WHERE `uniacid` = :uniacid AND `id` = :id', array(':uniacid' => $_W['uniacid'],':id' => $uid ) );
		
		include $this->template('reg3');
	}
	private function initSetting($setting) {
        define('WXPAY_APPID', $setting['pay_appid']);
        define('WXPAY_MCHID', $setting['pay_mchid']);
        define('WXPAY_KEY', $setting['pay_key']);
        define('WXPAY_APPSECRET', $setting['pay_appsecret']);
        define('WXPAY_PAY_URL', $setting['pay_url']);
    }
	public function doMobileAuth() {
        global $_W,$_GPC;

        $setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid'] ));
		
		$openid = $_GPC['dopenid'];
        if(empty($_GPC['code'])){
            $back_url ="http://www.hfwxz.com/app/index.php?i=".$_W['uniacid']."&c=entry&do=auth&dopenid=$openid&m=hleiya_xsc";
			
			
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe16c6883bae4f8a3&redirect_uri=".urlencode($back_url)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			
            header("location:".$url);
            exit;
        }

        $param=array();
        $param ['appid']='wxe16c6883bae4f8a3';
        $param ['secret'] = '943c3a00a09c9374fa2d24ceba96d6f7';
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
		
        $user = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_user') . ' WHERE `uniacid` = :uniacid AND `openid` = :openid', array(':uniacid' => $_W['uniacid'],':openid' => $wxuser['openid'] ) );

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'nickname'=>$wxuser['nickname'],
            'headimgurl'=>$wxuser['headimgurl'],
            'province'=>$wxuser['province'],
            'city'=>$wxuser['city'],
            'sex'=>$wxuser['sex'],
            'dopenid'=>$openid,
            'openid'=>$wxuser['openid']
        );
        if($user){
            pdo_update('hleiya_penny_user', $data, array('id' => $user['id']));
        }else{
            pdo_insert('hleiya_penny_user', $data);
            $user['id']=pdo_insertid();
        }
        isetcookie('hleiya_xsc_uid'.$_W['uniacid'], $user['id'],86400*7);

        header("location: ./index.php?i=".$_W['uniacid']."&c=entry&dopenid=$openid&do=index&ss=1&m=hleiya_xsc");
        exit;
    }
	public function doWebSetting() {
		global $_W,$_GPC;

        $setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        
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
                pdo_update('hleiya_penny_setting', $data, array('id' => $setting['id']));
                $id=$setting['id'];
            }else{
                pdo_insert('hleiya_penny_setting', $data);
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
		$setting = pdo_fetch('SELECT * FROM ' . tablename('hleiya_penny_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

        if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'paysetting'){
            $data=array(
				'uniacid'=>10,
                'pay_appid'=>$_GPC['pay_appid'],
                'pay_mchid'=>$_GPC['pay_mchid'],
                'pay_key'=>$_GPC['pay_key'],
                'pay_appsecret'=>$_GPC['pay_appsecret'],
                'pay_fee'=>$_GPC['pay_fee'],
                'pay_url'=>$_GPC['pay_url']
            );
			
            if($setting){
                pdo_update('hleiya_penny_setting', $data, array('id' => $setting['id']));
                $id=$setting['id'];
            }else{
                pdo_insert('hleiya_penny_setting', $data);
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

        
        $sql = "SELECT a.sort,a.total_fee,a.dateline,b.nickname,b.headimgurl FROM ".tablename('hleiya_penny_pay') . " a LEFT JOIN " . tablename('hleiya_penny_user') . " b ON a.uid=b.id WHERE $condition ORDER BY a.dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql);
        
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hleiya_penny_pay')." a WHERE $condition");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('pay_list');
	}

}