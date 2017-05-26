<?php
/**
 * 商家拼图模块微站定义
 *
 * @author 蜂窝科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

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


	public function  doMobileIndex(){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
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
	
	public function doMobileGetIndex(){
		global $_W,$_GPC;
		$fid=$_GPC['fid'];
		$uname=$_GPC['uname'];
		$tel=$_GPC['tel'];
		$score=$_GPC['score'];

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

		$res['code']=200;

		echo json_encode($res);

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