<?php
/*
 * 创始人管理
 * 作者：liuqiang
 * 日期: 2018/9/10
 */
class FounderAction extends CommonAction {
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
	// 查看列表
	public function index()
	{
		// 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
		// 筛选
		$status = $this->_param('status');
		$store_type = $this->_param('store_type');
		$mobile = $this->_param('mobile');
		if($status){
			switch ($status) {
				case 4:
					$map['f.status'] = 0;
					$status = 4;
					break;
				default:
					$map['f.status'] = $status;
					break;
			}
			$this->assign('status',$status);
		}
		if($store_type){
			$map['f.store_type'] = $store_type;
			$this->assign('store_type',$store_type);
		}
		if($mobile){
			$map['u.mobile'] = $mobile;
			$this->assign('mobile',$mobile);
		}
		import('ORG.Util.Page'); // 导入分页类
        $count = $this->founder->alias('f')
				->join('uboss_shop_cate c on c.cate_id = f.shop_cate_id')
				->join('uboss_users u on u.user_id = f.user_id')
				->join('uboss_shop s on s.shop_id = f.shop_id')
				->field('f.shop_id,f.founder_id,f.create_time,f.status,u.user_id,c.cate_name,u.realname,u.idcard_zimgs,u.idcard_fimgs,u.idcardno,u.mobile,f.store_type')
				->where($map)
				->count(); 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		$data = $this->founder->alias('f')
				->join('uboss_shop_cate c on c.cate_id = f.shop_cate_id')
				->join('uboss_users u on u.user_id = f.user_id')
				->join('uboss_shop s on s.shop_id = f.shop_id')
				->field('f.shop_id,f.founder_id,f.create_time,f.status,u.user_id,c.cate_name,u.realname,u.idcard_zimgs,u.idcard_fimgs,u.idcardno,u.mobile,f.store_type')
				->where($map)
				->limit($Page->firstRow . ',' . $Page->listRows)
				->order('create_time desc')
				->select();
		$this->assign('list',$data);
		$this->assign('page',$show);
		$this->assign('count',$count);
		$this->display();
	}
	// 选择用户
	public function userinfo()
	{
		$mobile = $this->_param('mobile');
		if($mobile){
			$map['mobile'] = $mobile;
			$this->assign('mobile',$mobile);
		}
		import('ORG.Util.Page'); // 导入分页类
        $count = M('users')->where($map)->count(); 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		$data = M('users')->where($map)->select();
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->assign('count',$count);
		$this->display();
	}
	// 创始人申请数据
	public function appli()
	{
		$this->display();
	}
	public function add()
	{
		if(IS_POST){
			$_POST['create_time'] = time();
			$r = $this->founder->add($_POST);
			if($r){
				$this->success('添加成功',U('index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			// 选择用户
	        $user = M('users')->where('is_reg = 1')->select();
	        $this->assign('users',$user);
	        // 分类
        	$this->assign('cates', D('Shopcate')->fetchAll());
			$this->display();	
		}
	}
	// 生成U店
	public function add_u()
	{
		if(IS_POST){
			$_POST['password'] = md5($_POST['password']);
            $_POST['create_time'] = time();
            $_POST['create_ip'] = get_client_ip();
			$founder_id = $this->_param('founder_id');
			$r = $this->shop->add($_POST);
			if($r){
				$data['status'] = 3;
				$data['shop_id'] = $r;
				$this->founder->where('founder_id = '.$founder_id)->save($data);
				$this->baoSuccess('生成成功',U('index'));
			}else{
				$this->baoError('生成失败');
			}
		}else{
			$founder_id = $this->_param('id');
			$founder = $this->founder
					   ->where('founder_id = '.$founder_id)
					   ->find();
			$founder['cate_name'] = $this->cates
									->where('cate_id = '.$founder['shop_cate_id'])
									->getField('cate_name');
			$this->assign('founder',$founder);
			$userinfo = $this->users
						->find($founder['user_id']);
			$this->assign('userinfo',$userinfo);
			// 分类
	        $this->assign('cates', D('Shopcate')->fetchAll());
			$this->display();
		}
	}
	// 审核
	public function refuse()
	{
		if(IS_POST){
			$arr['founder_id'] = intval($_POST['founder_id']);
			$arr['reason'] = $_POST['reason'];
			$arr['status'] = 0;
			$r  = $this->founder->save($arr);
			if($r){
				$this->baoSuccess('已拒绝',U('index'));
			}
		}else{
			$this->display();
		}
	}
	// 原因
	public function refuse_cause()
	{
		$founder_id = $this->_param('founder_id');
		$reason = $this->founder->where('founder_id = '.$founder_id)->getField('reason');
		$this->assign('reason',$reason);
		$this->display();
	}
	public function ajax_ok()
	{
		$id = intval($_POST['id']);
		$arr['status'] = 2;
		$r  = $this->founder->where('founder_id = '.$id)->save($arr);
		echo $r;
	}
	// 查看U店
	public function look_info()
	{
		$id = $this->_param('id');
		 // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
        $map['f.shop_id'] = $id;
		// 选择U店
        $shop = $this->shop->alias('s')
        		->join('uboss_founder f on f.founder_id = s.founder_id')
        		->join('uboss_users u on u.user_id = f.user_id')
        		->where($map)
				->field('s.*,f.store_type,u.realname,u.money,u.asset')
				->find();
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
		$res = M('shop_cate')->where('cate_id = '.$shop['cate_id'])->field('cate_name')->find();
		$shop['cate_name'] = $res['cate_name'];
		// 城市
		$city = $this->city
				->where('city_id = '.$shop['city_id'])
				->getField('name');
		$shop['city'] = $city;
		// 区域
		$area = $this->area
				->where('area_id = '.$value['area_id'])
				->getField('area_name');
		$shop['area'] = $area;
		// 总监
		$shop['zj'] = $this->apply
							->where('shop_id = '.$shop['shop_id'].' and apply_position = 1 and status = 2')
							->count().'/'.$zj;
		// 经理
		$shop['jl'] = $this->apply
							->where('shop_id = '.$shop['shop_id'].' and apply_position = 2 and status = 2')
							->count().'/'.$jl;
		// 主管
		$shop['zg'] = $this->apply
							->where('shop_id = '.$shop['shop_id'].' and apply_position = 3 and status = 2')
							->count().'/'.$zg;
		$this->assign('shop',$shop);
		// 组成人员信息
		$type = $this->_param('pos');
		if($type){
			$where['a.apply_position'] = $type;
		}
		$this->assign('type',$type);
		$where['a.shop_id'] = $shop['shop_id'];
		$where['a.status'] = 2;
		$data = $this->apply->alias('a')
				->where($where)
				->field('user_id')
				->select();
		foreach ($data as $key => $value) {
			$users[] = $this->users->where('user_id = '.$value['user_id'])->find();
		}
		$this->assign('user',$users);
		// 分红历史
		$his = M('divide_money')->where('shop_id = '.$id)->order('create_time desc')->select();
		foreach ($his as $key => $value) {
			$his[$key]['name'] = $this->_admin['username'];
			$data = M('user_money_logs')->where('divide_id = '.$value['divide_id'])->select();
			foreach ($data as $key1 => $value1) {
				$data[$key1]['username'] = $this->users->where('user_id = '.$value1['user_id'])->getField('realname').'['.$this->users->where('user_id = '.$value1['user_id'])->getField('mobile').']';
			}
			$his[$key]['child'] = $data;
		}
		$this->assign('his',$his);
		$this->display();
	}
	// 开始运营
	public function start_business()
	{
		$data['shop_id'] = $this->_param('shop_id');
		$data['status'] = 1;
		$this->shop->save($data);
		$this->baoSuccess('已经开始运营',U('look_info',array('id' => $data['shop_id'])));
	}
	// 开始运营
	public function end_business()
	{
		$data['shop_id'] = $this->_param('shop_id');
		$data['status'] = 0;
		$this->shop->save($data);
		$this->baoSuccess('已经停止运营',U('look_info',array('id' => $data['shop_id'])));
	}
	// 发放分红
	public function give_money()
	{
		
		$shop_id = $this->_param('shop_id');
		$money = $this->_param('money');
		// 发放金额历史
		$divide['admin_id'] = $this->_admin['admin_id'];
		$divide['shop_id'] = $shop_id;
		$divide['total_money'] = $money*100;
		$divide['create_time'] = time();
		$divide_id = M('divide_money')->add($divide);
		if(!$divide_id){
			$this->baoError('数据有误');
		}
		/** 创始人分红**/
		// 开启事务
		M('users')->startTrans();
		$data['divide_id'] = $divide_id;
		$founder = $money*19;
		$data['money'] = $founder;
		$data['shop_id'] = $shop_id;
		$data['user_id'] = M('founder')->where('shop_id = '.$shop_id)->getField('user_id');
		// 创始人余额增加
		$fmoney = M('users')->where('user_id = '.$data['user_id'])->getField('money');
		$allmoney['money'] = floor($fmoney) + $founder;
		$allmoney['user_id'] = $data['user_id'];
		$r = M('users')->save($allmoney);
		if($r){
			M('users')->commit();
		}else{
			M('users')->rollback();
		}
		$data['intro'] = '创始人U店分红';
		$data['status'] = 2;
		$data['type'] = 4;
		$data['create_time'] = time();
		$data['create_ip'] = get_client_ip();
		M('user_money_logs')->add($data);
		/**合伙人发货**/
		// 店类型
		$ustore_type = M('founder')->where('shop_id = '.$shop_id)->getField('store_type');
		// 股份
		switch ($ustore_type) {
			// 旗舰
			case 1:
				$allotment = 950000;
				break;
			// 核心
			case 2:
				$allotment = 340000;
				break;
			// 人气
			case 3:
				$allotment = 100000;
				break;
			default:
				# code...
				break;
		}
		$user = M('apply')->where('shop_id = '.$shop_id)->select();
		// 合伙人 每股价值
		$per_allotment = $money*30/$allotment;
		foreach ($user as $key => $value) {
			$user['shop_id'] = $shop_id;
			$user['user_id'] = $value['user_id'];
			switch ($value['apply_position']) {
				case 1:
					// 总监   1万股
					$user['money'] = floor(10000*$per_allotment);
					$user['intro'] = '总监U店分红';
					break;
				case 2:
					// 经理   3000股
					$user['money'] = floor(3000*$per_allotment);
					$user['intro'] = '经理U店分红';
					break;
				case 3:
					// 主管   1000股
					$user['money'] = floor(1000*$per_allotment);
					$user['intro'] = '主管U店分红';
					break;
				default:
					# code...
					break;
			}
			$user['status'] = 2;
			$user['type'] = 4;
			$user['create_time'] = time();
			$user['create_ip'] = get_client_ip();
			$user['divide_id'] = $divide_id;
			$r = M('user_money_logs')->add($user);
			if($r){
				// 用户余额增加
				$money = M('users')->where('user_id = '.$value['user_id'])->getField('money');
				$allmoney['money'] = floor($money) + $user['money'];
				$allmoney['user_id'] = $value['user_id'];
				M('users')->save($allmoney);
			}
		}
		$this->baoSuccess('发放成功',U('look_info',array('id' => $shop_id)));
	}
	// 身份证放大
    public function ajax_select_sfz()
    {
        $id = $this->_param('id');
        $type = $this->_param('type');
        if($type == 1){
            $card = 'idcard_zimgs';
        }
        if($type == 2){
            $card = 'idcard_fimgs';
        }
        echo $this->users->where('user_id = '.$id)->getField($card);
    }
}