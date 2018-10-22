<?php
/*
 * 提现管理
 * 作者：liuqiang
 * 日期: 2018/9/28
 */
class UserfinanceAction extends CommonAction {
	public function _initialize()
	{
		parent::_initialize();
		$this->user_money_logs = M('user_money_logs');
		$this->users = M('users');
	}
	// 查看列表
	public function index()
	{
		$status = $this->_param('status');
		if($status){
			switch ($status) {
				case 1:
					$map['l.status'] = 1;
					break;
				case 2:
					$map['l.status'] = 2;
					break;
				case 3:
					$map['l.status'] = 0;
					break;
				default:
					# code...
					break;
			}
			$this->assign('status',$status);
		}

		$map['l.type'] = 3;
		
		$user = $this->user_money_logs->alias('l')
				->join('uboss_user_cash_account a on a.log_id = l.log_id')
				->field('l.*,a.account_type,a.account')
				->where($map)
				->order('create_time desc')
				->select();
		foreach ($user as $key => $value) {
			$user[$key]['username'] = $this->users->where('user_id = '.$value['user_id'])->getField('realname');
			$user[$key]['ymoney'] = $this->users->where('user_id = '.$value['user_id'])->getField('money');
			$user[$key]['tmoney'] = $value['money']*(1-C('SET_REMIND')/100);
		}
		$this->assign('user',$user);
		$this->display();
	}
	// 审核
	public function ok()
	{	
		$tran = $this->users->startTrans();
		$data['log_id'] = $this->_param('log_id');
		$data['status'] = 2;
		$data['ok_time'] = time();
		$r = $this->user_money_logs->save($data);
		if($r){
			$user_id = $this->user_money_logs->where('log_id = '.$data['log_id'])->getField('user_id');
			$smoney = $this->user_money_logs->where('log_id = '.$data['log_id'])->getField('money');
			$money = $this->users->where('user_id = '.$user_id)->getField('money');
			$money -= $smoney;
			$arr['user_id'] = $user_id;
			$arr['money'] = $money;
			$res = $this->users->save($arr);
			if($res){
				$this->users->commit();
			}else{
				$this->users->rollback();
			}
		}
		$this->baoSuccess('审核通过',U('index'));
	}
	public function refuse()
	{
		if(IS_POST){
			$data['log_id'] = $this->_param('log_id');
			$data['status'] = 0;
			$data['reason'] = $this->_param('reason');
			$data['ok_time'] = time();
			$this->user_money_logs->save($data);
			$this->baoSuccess('审核不通过',U('index'));
		}else{
			$this->display();
		}
	}
	public function refuse_cause()
	{
		$log_id = $this->_param('log_id');
		$reason = $this->user_money_logs->where('log_id = '.$log_id)->getField('reason');
		$this->assign('reason',$reason);
		$this->display();
	}
	// 流水
	public function account()
	{
		$type = $this->_param('type');
		$mobile = $this->_param('mobile');
		if($type){
			$map['l.type'] = $type;
			$this->assign('type',$type);
		}
		if($mobile){
			$map['u.mobile'] = $mobile;
			$this->assign('mobile',$mobile);
		}
		import('ORG.Util.Page'); // 导入分页类
		$count = $this->user_money_logs
				->where($map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		// 1 充值 2 余额支付 22 扫码支付 3 提现 4 U店分红 5 消费分成 6 资产变现 
		$data = $this->user_money_logs->alias('l')
				->join('uboss_users u on u.user_id = l.user_id')
				->where($map)
				->limit($Page->firstRow . ',' . $Page->listRows)
				->order('create_time desc')
				->field('l.*,u.mobile,u.realname')
				->select();
		
		$this->assign('data',$data);
		$this->assign('count', $count);
        $this->assign('page', $show); // 赋值分页输出
		$this->display();
	}
	// 统计
	public function count_account()
	{
		// 统计
		$dat = $this->user_money_logs->group('user_id')->select();
		$res = array();
		foreach ($dat as $key => $value) {
			$res[] = $this->user_money_logs->field("sum(money) money,type,user_id")->where('user_id = '.$value['user_id'])->group('type')->select();
		}
		foreach ($res as $key => $value) {
			foreach ($value as $k => $val) {
				$res[$key][$k]['username'] = $this->users->where('user_id = '.$val['user_id'])->getField('realname');
				$res[$key][$k]['zc'] = $this->users->where('user_id = '.$val['user_id'])->getField('asset');
				$res[$key][$k]['yuer'] = $this->users->where('user_id = '.$val['user_id'])->getField('money');
				switch ($val['type']) {
					case 1:
						$res[$key][$k]['type'] = '充值';
						break;
					case 2:
						$res[$key][$k]['type'] = '余额支付';
						break;
					case 3:
						$res[$key][$k]['type'] = '提现';
						break;
					case 4:
						$res[$key][$k]['type'] = 'U店分红';
						break;
					case 5:
						$res[$key][$k]['type'] = '消费分成';
						break;
					case 6:
						$res[$key][$k]['type'] = '资产变现';
					case 7:
						$res[$key][$k]['type'] = '余额退款';
						break;
					case 22:
						$res[$key][$k]['type'] = '扫码支付';
						break;
					default:
						# code...
						break;
				}
			}
		}
		$arr = array();
		foreach ($res as $key => $value) {
			foreach ($value as $k => $val) {
				$arr[$val['username'].'-'.$val['zc'].'-'.$val['yuer']][] = $val;
			}
		}
		$this->assign('arr',$arr);
		$this->display();
	}
}
