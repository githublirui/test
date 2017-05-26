<?php
/**
 * 商家拼图模块微站定义
 *
 * @author 蜂窝科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/microb_redpack');
class PingtuModuleSite extends WeModuleSite {
 public $activitytalbe = "xhw_pingtu";
 
	public function doWebactivity() {
        global $_W, $_GPC;
		
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        
        if ($operation == 'post') { // 添加
            $id = intval($_GPC['id']);
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，游戏删除或不存在！', '', 'error');
                }
                
                $item['begintime'] = date("Y-m-d  H:i", $item['begintime']);
                $item['endtime'] = date("Y-m-d  H:i", $item['endtime']);
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['name'])) {
                    message('请输入活动名称!');
                }
                
                if (empty($_GPC['ac_pic'])) {
                    message('请选广告图片！');
                }
                
                if (empty($_GPC['link'])) {
                    message('请输入引导关注素材地址！');
                }
                
                $data = array(
                    'weid' => $_W['uniacid'],
                    'name' => $_GPC['name'],
                    'ac_pic' => $_GPC['ac_pic'],
                    'ppt1' => $_GPC['ppt1'],
                    'ppt2' => $_GPC['ppt2'],
                    'ppt3' => $_GPC['ppt3'],
					'ppt4' => $_GPC['ppt4'],
					'ppt5' => $_GPC['ppt5'],
					'pt1' => $_GPC['pt1'],
                    'pt2' => $_GPC['pt2'],
                    'pt3' => $_GPC['pt3'],
					'pt4' => $_GPC['pt4'],
					'pt5' => $_GPC['pt5'],
					'pt6' => $_GPC['pt6'],
					'time1' => $_GPC['time1'],
                    'time2' => $_GPC['time2'],
                    'time3' => $_GPC['time3'],
					'time4' => $_GPC['time4'],
					'time5' => $_GPC['time5'],
					'time6' => $_GPC['time6'],
					'h1' => $_GPC['h1'],
                    'h2' => $_GPC['h2'],
                    'h3' => $_GPC['h3'],
					'h4' => $_GPC['h4'],
					'h5' => $_GPC['h5'],
					'h6' => $_GPC['h6'],
					'l1' => $_GPC['l1'],
                    'l2' => $_GPC['l2'],
                    'l3' => $_GPC['l3'],
					'l4' => $_GPC['l4'],
					'l5' => $_GPC['l5'],
					'l6' => $_GPC['l6'],
					'icon' => $_GPC['icon'],
					'bgcolor' => $_GPC['bgcolor'],
					'bgpic' => $_GPC['bgpic'],
					
                    'link' => $_GPC['link'],
                );
                if (! empty($id)) {
                    pdo_update($this->activitytalbe, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->activitytalbe, $data);
                }
                message('更新拼图游戏模块成功成功！', $this->createWebUrl("activity", array(
                    "op" => "display"
                )), 'success');
            }
        } elseif ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            
            $psize = 20;
            
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE weid = '{$_W['uniacid']}'  ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->activitytalbe) . " WHERE weid = '{$_W['uniacid']}'");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            // 删除活动
            pdo_delete($this->activitytalbe, array(
                'id' => $id
            ));
            
            message('删除成功！', referer(), 'success');
        }
        
        load()->func('tpl');
        include $this->template('activity');
	}

	function random($length, $numeric = FALSE) {
		$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
		$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
		if($numeric) {
			$hash = '';
		} else {
			$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
			$length--;
		}
		$max = strlen($seed) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $seed{mt_rand(0, $max)};
		}
		return $hash;
	}
	function strexists($string, $find) {
		return !(strpos($string, $find) === FALSE);
	}
	function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
		$urlset = parse_url($url);
		if(empty($urlset['path'])) {
			$urlset['path'] = '/';
		}
		if(!empty($urlset['query'])) {
			$urlset['query'] = "?{$urlset['query']}";
		}
		if(empty($urlset['port'])) {
			$urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
		}
		
		if ($this->strexists($url, 'https://') && !extension_loaded('openssl')) {
			if (!extension_loaded("openssl")) {
				message('请开启您PHP环境的openssl');
			}
		}
		if(function_exists('curl_init') && function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlset['scheme']. '://' .$urlset['host'].($urlset['port'] == '80' ? '' : ':'.$urlset['port']).$urlset['path'].$urlset['query']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			if($post) {
				if (is_array($post)) {
					$filepost = false;
					foreach ($post as $name => $value) {
						if (substr($value, 0, 1) == '@') {
							$filepost = true;
							break;
						}
					}
					if (!$filepost) {
						$post = http_build_query($post);
					}
				}
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSLVERSION, 1);
			if (defined('CURL_SSLVERSION_TLSv1')) {
				curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
			}
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
			if (!empty($extra) && is_array($extra)) {
				$headers = array();
				foreach ($extra as $opt => $value) {
					if (strexists($opt, 'CURLOPT_')) {
						curl_setopt($ch, constant($opt), $value);
					} elseif (is_numeric($opt)) {
						curl_setopt($ch, $opt, $value);
					} else {
						$headers[] = "{$opt}: {$value}";
					}
				}
				if(!empty($headers)) {
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				}
			}
			$data = curl_exec($ch);
			$status = curl_getinfo($ch);
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			if($errno || empty($data)) {
				return error(1, $error);
			} else {
				
				return $this->ihttp_response_parse($data);
			}
		}
		$method = empty($post) ? 'GET' : 'POST';
		$fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
		$fdata .= "Host: {$urlset['host']}\r\n";
		if(function_exists('gzdecode')) {
			$fdata .= "Accept-Encoding: gzip, deflate\r\n";
		}
		
		$fdata .= "Connection: close\r\n";
		if (!empty($extra) && is_array($extra)) {
			foreach ($extra as $opt => $value) {
				if (!$this->strexists($opt, 'CURLOPT_')) {
					$fdata .= "{$opt}: {$value}\r\n";
				}
			}
		}
		$body = '';
		if ($post) {
			if (is_array($post)) {
				$body = http_build_query($post);
			} else {
				$body = urlencode($post);
			}
			$fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
		} else {
			$fdata .= "\r\n";
		}
		if($urlset['scheme'] == 'https') {
			$fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
		} else {
			$fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
		}
		stream_set_blocking($fp, true);
		stream_set_timeout($fp, $timeout);
		if (!$fp) {
			return error(1, $error);
		} else {
			fwrite($fp, $fdata);
			$content = '';
			while (!feof($fp))
				$content .= fgets($fp, 512);
			fclose($fp);
			return $this->ihttp_response_parse($content, true);
		}
	}

	function ihttp_response_parse($data, $chunked = false) {
		$rlt = array();
		$pos = strpos($data, "\r\n\r\n");
		$split1[0] = substr($data, 0, $pos);
		$split1[1] = substr($data, $pos + 4, strlen($data));
		
		$split2 = explode("\r\n", $split1[0], 2);
		preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
		$rlt['code'] = $matches[2];
		$rlt['status'] = $matches[3];
		$rlt['responseline'] = $split2[0];
		$header = explode("\r\n", $split2[1]);
		$isgzip = false;
		$ischunk = false;
		foreach ($header as $v) {
			$row = explode(':', $v);
			$key = trim($row[0]);
			$value = trim($row[1]);
			if (is_array($rlt['headers'][$key])) {
				$rlt['headers'][$key][] = $value;
			} elseif (!empty($rlt['headers'][$key])) {
				$temp = $rlt['headers'][$key];
				unset($rlt['headers'][$key]);
				$rlt['headers'][$key][] = $temp;
				$rlt['headers'][$key][] = $value;
			} else {
				$rlt['headers'][$key] = $value;
			}
			if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
				$isgzip = true;
			}
			if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
				$ischunk = true;
			}
		}
		if($chunked && $ischunk) {
			$rlt['content'] = $this->ihttp_response_parse_unchunk($split1[1]);
		} else {
			$rlt['content'] = $split1[1];
		}
		if($isgzip && function_exists('gzdecode')) {
			$rlt['content'] = gzdecode($rlt['content']);
		}

		$rlt['meta'] = $data;
		if($rlt['code'] == '100') {
			return $this->ihttp_response_parse($rlt['content']);
		}
		return $rlt;
	}
	function ihttp_response_parse_unchunk($str = null) {
		if(!is_string($str) or strlen($str) < 1) {
			return false; 
		}
		$eol = "\r\n";
		$add = strlen($eol);
		$tmp = $str;
		$str = '';
		do {
			$tmp = ltrim($tmp);
			$pos = strpos($tmp, $eol);
			if($pos === false) {
				return false;
			}
			$len = hexdec(substr($tmp, 0, $pos));
			if(!is_numeric($len) or $len < 0) {
				return false;
			}
			$str .= substr($tmp, ($pos + $add), $len);
			$tmp  = substr($tmp, ($len + $pos + $add));
			$check = trim($tmp);
		} while(!empty($check));
		unset($tmp);
		return $str;
	}
	function array2xml($arr, $level = 1) {
		$s = $level == 1 ? "<xml>" : '';
		foreach($arr as $tagname => $value) {
			if (is_numeric($tagname)) {
				$tagname = $value['TagName'];
				unset($value['TagName']);
			}
			if(!is_array($value)) {
				$s .= "<{$tagname}>".(!is_numeric($value) ? '<![CDATA[' : '').$value.(!is_numeric($value) ? ']]>' : '')."</{$tagname}>";
			} else {
				$s .= "<{$tagname}>" . array2xml($value, $level + 1)."</{$tagname}>";
			}
		}
		$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
		return $level == 1 ? $s."</xml>" : $s;
	}


	function send($open_id, $fee, $record_id) {

		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	//    load()->func('communication');
		
		$hongbao_mchid='1220658501';
		$hongbao_appid='wx9862b2b2638b5284';
		$hongbao_provider='南翔智慧汽车城';
		$hongbao_wish='南翔智慧汽车城';
		$hongbao_ip='121.40.83.102';
		$hongbao_title='南翔智慧汽车城红包';
		$hongbao_remark='南翔智慧汽车城红包';
		$hongbao_password='male3652qqcomQSWHCBCOMhongbao888';

		$pars = array();
		$pars['nonce_str'] = $this->random(32);
		$pars['mch_billno'] = $hongbao_mchid . date('ymd') . sprintf('%010d', $record_id);
		$pars['mch_id'] = $hongbao_mchid;
		$pars['wxappid'] = $hongbao_appid;
		$pars['nick_name'] = $hongbao_provider;
		$pars['send_name'] = $hongbao_provider;
		$pars['re_openid'] = $open_id;
		$pars['total_amount'] = $fee;
		$pars['min_value'] = $fee;
		$pars['max_value'] = $fee;
		$pars['total_num'] = 1;
		$pars['wishing'] = $hongbao_wish;
		$pars['client_ip'] = $hongbao_ip;
		$pars['act_name'] = $hongbao_title;
		$pars['remark'] = $hongbao_remark;
		$pars['logo_imgurl'] = 'tt';
		$pars['share_content'] = 'tt';
		$pars['share_imgurl'] = 'tt';
		$pars['share_url'] = 'tt';


		ksort($pars, SORT_STRING);
		
		$string1 = '';
		foreach($pars as $k => $v) {
			$string1 .= "{$k}={$v}&";
		}
		$string1 .= "key=".$hongbao_password;
		$pars['sign'] = strtoupper(md5($string1));
		
		$xml = $this->array2xml($pars);
		
		$extras = array();
		$extras['CURLOPT_CAINFO'] = './cert/rootca.pem.' . $W['uniacid'];
		$extras['CURLOPT_SSLCERT'] = './cert/apiclient_cert.pem.' . $W['uniacid'];
		$extras['CURLOPT_SSLKEY'] = './cert/apiclient_key.pem.' . $W['uniacid'];

		$procResult = null;
		
		$resp = $this->ihttp_request($url, $xml, $extras);
		
		if($this->is_error($resp)) {
	//        $setting = $this->module['config'];
	//        $setting['api']['error'] = $resp['message'];
	//        $this->saveSettings($setting);
	//        $procResult = $resp;
			return false;
		} else {
			$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
			$dom = new DOMDocument();
			if($dom->loadXML($xml)) {
				$xpath = new DOMXPath($dom);
				$code = $xpath->evaluate('string(//xml/return_code)');
				$ret = $xpath->evaluate('string(//xml/result_code)');
				
				if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
				   return true;
				} else {
					$error = $xpath->evaluate('string(//xml/err_code_des)');
					
					return $error;
	//                $setting = $this->module['config'];
	//                $setting['api']['error'] = $error;
	//                $this->saveSettings($setting);
	//                $procResult = error(-2, $error);
				}
			} else {
				return 'error response';
	//            $procResult = error(-1, 'error response');
			}
		}


	}

	public function doMobileAuth() {
        global $_GPC, $_W;
        session_start();
        $code = $_GPC['code'];
        load()->func('communication');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx9862b2b2638b5284&secret=3a92840ad101a68573fd604c236702c3&code={$code}&grant_type=authorization_code";
        $resp = ihttp_get($url);
        if(is_error($resp)) {
            message('系统错误, 详情: ' . $resp['message']);
        }
        $auth = @json_decode($resp['content'], true);
		
        if(is_array($auth) && !empty($auth['openid'])) {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$auth['access_token']}&openid={$auth['openid']}&lang=zh_CN";
            $resp = ihttp_get($url);
			
            if(is_error($resp)) {
                message('系统错误');
            }
            $info = @json_decode($resp['content'], true);
            if(is_array($info) && !empty($info['openid'])) {

				$sql = 'SELECT * FROM ' . tablename('xhw_pingtu_info') . ' WHERE `uniacid`='.$_W['uniacid'].' AND `openid` ="'.$_SESSION['openid'].'"';
				$ret= pdo_fetch($sql);
				if(!$ret){
					$user['uniacid']         = $_W['uniacid'];
					$user['openid']          = $info['openid'];
					$user['uid']         = $info['uid'];
					$user['nickname']        = $info['nickname'];
					$user['gender']          = $info['sex'];
					$user['city']            = $info['city'];
					$user['state']           = $info['province'];
					$user['avatar']          = $info['headimgurl'];
					$user['country']         = $info['country'];
					if(!empty($user['avatar'])) {
						$user['avatar'] = rtrim($user['avatar'], '0');
						$user['avatar'] .= '132';
					}
					
					
					$ret = pdo_insert('xhw_pingtu_info', $user);
				}

                $_SESSION['__:proxy:openid'] = $user['openid'];
                $forward = $_SESSION['__:proxy:forward'];
				
                header('Location: ' . $forward);
                exit();
            }
        }
        message('系统错误');
    }
	protected function auth() {
        global $_W;
        //return array('uid' => '1', 'gender' => '男', 'state' => '山西', 'city' => '太原');
        #debug
        session_start();
        $openid = $_SESSION['__:proxy:openid'];

        if(!empty($openid)) {
			
            $sql = 'SELECT * FROM ' . tablename('xhw_pingtu_info') . ' WHERE `uniacid`='.$_W['uniacid'].' AND `openid` ="'.$openid.'"';
			$exists= pdo_fetch($sql);
			
            if(!empty($exists)) {
                return $exists;
            }
        }
        
        $callback = $_W['siteroot'] . 'app' . substr($this->createMobileUrl('auth'), 1);
        $callback = urlencode($callback);
        $state = $_SERVER['REQUEST_URI'];
        $stateKey = substr(md5($state), 0, 8);
        $_SESSION['__:proxy:forward'] = $state;
        $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx9862b2b2638b5284&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$stateKey}#wechat_redirect";
        header('Location: ' . $forward);
        exit;
    }
	function is_error($data) {
		if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
			return false;
		} else {
			return true;
		}
	}

	public function  doMobileIndex(){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		
		$user = $this->auth();
		
		$fid=$_GPC['fid'];
		//判断是否微信客户端
		//checkauth();
		
		require_once MODULE_ROOT.'/checkWX.class.php';
		$cwx=new checkWX();
		$cwx->checkIsWxClient($url);
		unset($cwx);
		
//判断结束
        $slides = pdo_fetchall("select * from ".tablename('xhw_pingtu')." where weid = ".$weid."  order by id desc");		
		include $this->template('index');
	}
	/**
	 * 礼品领取
	 */
	public function str_murl($url){
		global $_W,$_GPC;

		return $_W['siteroot'].'app'.str_replace('./','/',$url);

	}
	
	public function doMobileGethb(){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		
		$user = $this->auth();
		
		$fid=$_GPC['fid'];
		$id=$_GPC['hid'];
		//判断是否微信客户端
		//checkauth();
		
		require_once MODULE_ROOT.'/checkWX.class.php';
		$cwx=new checkWX();
		$cwx->checkIsWxClient($url);
		unset($cwx);
		
		//判断结束
        $slides = pdo_fetchall("select * from ".tablename('xhw_pingtu')." where weid = ".$weid."  order by id desc");		

		$uname=$_GPC['uname'];
		$tel=$_GPC['tel'];
		$score=$_GPC['score'];
		
		$registUser=pdo_fetch("select * from ".tablename("xhw_pingtu_user")." order by id desc limit 1");
		
		if($registUser['score']=='1'){
			$fee = rand(100, 150);
		}elseif($registUser['score']=='2'){
			$fee = rand(150, 200);
		}elseif($registUser['score']=='3'){
			$fee = rand(200, 250);
		}elseif($registUser['score']=='4'){
			$fee = rand(250, 300);
		}elseif($registUser['score']=='5'){
			$fee = rand(300, 350);
		}elseif($registUser['score']=='6'){
			$fee = rand(350, 400);
		}

		$sql = 'SELECT * FROM ' . tablename('xhw_pingtu_info') . ' WHERE `uniacid`='.$_W['uniacid'].' AND `openid` ="'.$user['openid'].'" and fee !=""';
		
		$ret= pdo_fetch($sql);
		if($ret){
			$res['msg']="您已经领过红包了";
		}else{
			$send = $this->send($user['openid'], $fee, $registUser['id']);
			if($send === true){
				$rec['fee'] = $fee/100;
				$rec['pid'] = $registUser['id'];
				$rec['success'] = 'complete';
				$filter['openid'] = $user['openid'];
				$filter['uniacid'] = $_W['uniacid'];
				$rett = pdo_update('xhw_pingtu_info', $rec, $filter);
				
				
			}else{
				$rec['fee'] = $fee/100;
				$rec['pid'] = $registUser['id'];
				$rec['success'] = $send;
				$filter['openid'] = $user['openid'];
				$filter['uniacid'] = $_W['uniacid'];
				$rett = pdo_update('xhw_pingtu_info', $rec, $filter);

				$res['msg']="领取失败，请联系管理员";
			}
		}

		
		include $this->template('gethb');
	}
	public function doMobileGetIndex(){
		global $_W,$_GPC;
		$fid=$_GPC['fid'];
		$uname=$_GPC['uname'];
		$tel=$_GPC['tel'];
		$score=$_GPC['score'];
		//$user = $this->auth();
		$registUser=pdo_fetch("select * from ".tablename("xhw_pingtu_user")." where tel=:tel and weid=:weid",array(':tel'=>$tel,':weid'=>$W['uniacid']));

		$res=array();
		if(!empty($registUser)){

			$res['code']=501;
			$res['msg']="该手机号码已登记过信息";
			echo json_encode($res);
			exit;
		}

		$data=array(
		
			'weid'=>$_W['uniacid'],
			'fid'=>$fid,
			'uname'=>$uname,
			'tel'=>$tel,
			'score'=>$score,
			'createtime'=>TIMESTAMP
		);
		
		pdo_insert(xhw_pingtu_user,$data);
		$id= pdo_insertid();
		$res['msg']="信息提交成功";
		$res['code']=200;
		
		echo json_encode($res);exit;

	}
	
	public function doWebPicture() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = "weid = '{$_W['uniacid']}'";
		if (!empty($_GPC['keywords'])) {
			$condition .= " AND uname = '{$_GPC['keywords']}'";
		}
		$sql="select * from ".tablename('xhw_pingtu_user')." where weid = ".$weid."  order by score desc LIMIT ".(($pindex-1)*$psize).",".$psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(xhw_pingtu_user)." WHERE $condition");
		$pager = pagination($total, $pindex, $psize);		

		include $this->template('picture');
	}	
	public function doWebpost(){
		global $_W,$_GPC;
		$id = (int) $_GPC['id'];
		// 删除
		if($_GPC['op']=='delete'){
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename(xhw_pingtu_user)." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，信息不存在或是已经被删除！');
			}
			pdo_delete(xhw_pingtu, array('id' => $id));

			pdo_delete(xhw_pingtu_user, array('fid' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('post');
	}

}