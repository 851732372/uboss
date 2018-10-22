<?php
/*
 * 申请人管理
 * 作者：liuqiang
 * 日期: 2018/9/10
 */
class ApplyAction extends CommonAction {
	public function _initialize()
	{
		parent::_initialize();
		$this->founder = M('founder');
		$this->apply = M('apply');
		$this->shop = M('shop');
		$this->city = M('city');
		$this->area = M('area');
		$this->users = M('users');
		$this->cates = M('shop_cate');
	}
	// 选择U店
	public function select_ustore()
	{
		$status = $this->_param('status');
		$shop_name = $this->_param('shop_name');
		if($shop_name){
			$map['shop_name'] = array('like',"%$shop_name%");
			$this->assign('shop_name',$shop_name);
		}
		if($status){
			switch ($status) {
				case 1:
					$map['status'] = 1;
					break;
				case 2:
					$map['status'] = 0;
					break;
				default:
					# code...
					break;
			}
			$this->assign('status',$status);
		}
		$map['uboss_shop.founder_id'] = array('neq',0);
		import('ORG.Util.Page'); // 导入分页类
        $count = M('shop')->where($map)->count(); 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		$data = M('shop')->where($map)->select();
		foreach ($data as $key => $value) {
			$data[$key]['username'] = M('users')->where('user_id = '.$value['user_id'])->getField('realname');
			$shop = M('founder')->where('shop_id = '.$value['shop_id'])->find();
			switch ($shop['store_type']) {
				case 1:
					// 旗舰店
					$zj = 50;
					$jl = 100;
					$zg = 150;
					break;
				case 2:
					// 核心店
					$zj = 20;
					$jl = 30;
					$zg = 50;
					break;
				case 3:
					// 人气店
					$zj = 5;
					$jl = 10;
					$zg = 20; 
					break;
				default:
					# code...
					break;
			}
			// 总监
			$data[$key]['zj'] = $this->apply
								->where('shop_id = '.$shop['shop_id'].' and apply_position = 1 and status = 2')
								->count().'/'.$zj;
			// 经理
			$data[$key]['jl'] = $this->apply
								->where('shop_id = '.$shop['shop_id'].' and apply_position = 2 and status = 2')
								->count().'/'.$jl;
			// 主管
			$data[$key]['zg'] = $this->apply
								->where('shop_id = '.$shop['shop_id'].' and apply_position = 3 and status = 2')
								->count().'/'.$zg;
		}
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->assign('count',$count);
		$this->display();
	}
	public function add()
	{
		if(IS_POST){
			$_POST['create_time'] = time();
			$r = $this->apply->add($_POST);
			if($r){
				$this->baoSuccess('添加成功',U('through'));
			}else{
				$this->baoError('添加失败');
			}
		}else{
			$this->display();	
		}
	}
	// 审核
	public function refuse()
	{
		if(IS_POST){
			$arr['apply_id'] = $this->_param('apply_id');
			$arr['reason'] = $_POST['reason'];
			$arr['status'] = 0;
			$r  = $this->apply->save($arr);
			if($r){
				$this->baoSuccess('已拒绝',U('through'));
			}
		}else{
			$this->display();
		}
	}
	// 原因
	public function refuse_cause()
	{
		$apply_id = $this->_param('apply_id');
		$reason = $this->apply->where('apply_id = '.$apply_id)->getField('reason');
		$this->assign('reason',$reason);
		$this->display();
	}
	
	public function ajax_ok()
	{
		$id = intval($_POST['id']);
		$arr['status'] = 2;
		$r  = $this->apply->where('apply_id = '.$id)->save($arr);
		echo $r;
	}
	// 申请列表
	public function through()
	{
		 // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
		$data = $this->apply->select();
		foreach ($data as $key => $value) {
			$map['s.shop_id'] = $value['shop_id'];
			$list = $this->users
					->where('user_id = '.$value['user_id'])
					->field('realname,mobile')
					->find();
			// 姓名
			$data[$key]['realname'] = $list['realname'];
			$data[$key]['mobile'] = $list['mobile'];
			// 店铺
			$shop = $this->shop->alias('s')
					->where($map)
					->find();
			$data[$key]['shop_name'] = $shop['shop_name'];

			
		}
		$this->assign('list',$data);
		$this->display();
	}
	// 分红历史
	public function divide_his()
	{
		$user_id = $this->_param('user_id');
		$user = M('user_money_logs')->where('user_id = '.$user_id.' and type = 4')->order('create_time desc')->select();
		foreach ($user as $key => $value) {
			$user[$key]['shop_name'] = M('shop')->where('shop_id = '.$value['shop_id'])->getField('shop_name');
		}
		$money = M('user_money_logs')->where('user_id = '.$user_id.' and type = 4')->sum('money');
		$this->assign('money',$money);
		$this->assign('phis',$user);
		$this->display();
	}
}