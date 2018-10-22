<?php
/*
 * 城市提现管理
 * 作者：liuqiang
 * 日期: 2018/9/28
 */
class CityfinanceAction extends CommonAction {
	public function _initialize()
	{
		parent::_initialize();
		$this->city_money_logs = M('city_money_logs');
		$this->shop = M('shop');
		$this->ex = M('users_ex');
	}
	// 查看列表
	public function index()
	{
		if($_SESSION['admin']['admin_id'] != 1){
			$map['l.admin_id'] = $_SESSION['admin']['admin_id'];
		}
		$status = $this->_param('status');
		if($status){
			switch ($status) {
				case 1:
					$map['l.status'] = 0;
					break;
				case 2:
					$map['l.status'] = 2;
					break;
				case 3:
					$map['l.status'] = 1;
					break;
				default:
					# code...
					break;
			}
			$this->assign('status',$status);
		}

		$map['l.type'] = 1;
		import('ORG.Util.Page'); // 导入分页类
        $count = $this->city_money_logs->alias('l')
        		->where($where)
        		->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 16); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		$list = $this->city_money_logs->alias('l')
        		->join('uboss_admin a on a.admin_id = l.admin_id')
        		->join('uboss_city_ex e on e.admin_id = l.admin_id')
        		->where($map)
        		->order(array('l.create_time' => 'desc'))
        		->limit($Page->firstRow . ',' . $Page->listRows)
        		->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('count',$count);
		$this->display();
	}
	// 审核
	public function ok()
	{	
		M('admin')->startTrans();
		$data['city_log_id'] = $this->_param('city_log_id');
		$data['status'] = 2;
		$data['ok_time'] = time();
		$r = $this->city_money_logs->save($data);
		if($r){
			$admin_id = $this->city_money_logs->where('city_log_id = '.$data['city_log_id'])->getField('admin_id');
			$smoney = $this->city_money_logs->where('city_log_id = '.$data['city_log_id'])->getField('com_money');
			$money = M('admin')->where('admin_id = '.$admin_id)->getField('money');
			$money -= $smoney;
			$arr['admin_id'] = $admin_id;
			$arr['money'] = $money;
			$res = M('admin')->save($arr);
			if($res){
				M('admin')->commit();
			}else{
				M('admin')->rollback();
			}
		}
		$this->baoSuccess('审核通过',U('index'));
	}
	public function refuse()
	{
		if(IS_POST){
			$data['city_log_id'] = $this->_param('city_log_id');
			$data['status'] = 1;
			$data['reason'] = $this->_param('reason');
			$data['ok_time'] = time();
			$this->city_money_logs->save($data);
			$this->baoSuccess('审核不通过',U('index'));
		}else{
			$this->display();
		}
	}
	public function refuse_cause()
	{
		$city_log_id = $this->_param('city_log_id');
		$reason = $this->city_money_logs->where('city_log_id = '.$city_log_id)->getField('reason');
		$this->assign('reason',$reason);
		$this->display();
	}
	// 账号设置
	public function account()
	{
		if(IS_POST){
            $_POST['admin_id'] = $_SESSION['admin']['admin_id'];
            $r = M('city_ex')->where('admin_id = '.$_POST['admin_id'])->find();
            if($r){
            	$r = M('city_ex')->where('admin_id = '.$_POST['admin_id'])->save($_POST);
                $this->baoSuccess('修改成功',U('account'));
            }else{
                $res = M('city_ex')->add($_POST);
                $this->baoSuccess('修改成功',U('account'));
            }
        }else{
				$this->assign('detail',M('city_ex')->where('admin_id = '.$_SESSION['admin']['admin_id'])->find());
            $this->display();
        }
	}
	// 对账单
	public function orders()
	{
		if($_SESSION['admin']['admin_id'] != 1){
			$where['l.admin_id'] = $_SESSION['admin']['admin_id'];
		}
		// 城市管理者
		$admin = M('admin')->where('admin_id != 1')->select();
		$this->assign('admin',$admin);

		$admin_id = $this->_param('admin_id');
		if($admin_id){
			$where['l.admin_id'] = $admin_id;
			$this->assign('admin_id',$admin_id);
		}
		$audit = $this->_param('audit');
		if($audit){
			switch ($audit) {
				case 1:
					$where['l.audit'] = $audit;
					break;
				case 0:
					$where['l.audit'] = $audit;
					break;
				default:
					# code...
					break;
			}
			$this->assign('audit',$audit);
		}
		$where['l.type'] = "2";
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->city_money_logs->alias('l')
        		->where($where)
        		->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 16); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
       
        $list = $this->city_money_logs->alias('l')
        		->join('uboss_admin a on a.admin_id = l.admin_id')
        		->where($where)
        		->order(array('l.create_time' => 'desc'))
        		->limit($Page->firstRow . ',' . $Page->listRows)
        		->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('count', $count); // 赋值分页输出
        $this->display();
	}
}
