<?php
/*
 * U店管理
 * 作者：liuqiang
 * 日期: 2018/9/10
 */
class UstoreAction extends CommonAction {
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
		$type = $this->_param('type');
		$shop_name = $this->_param('shop_name');
		if($type){
			switch ($type) {
				case 1:
					$map['f.store_type'] = 1;
					break;
				case 2:
					$map['f.store_type'] = 2;
					break;
				case 3:
					$map['f.store_type'] = 3;
					break;
				default:
					# code...
					break;
			}
			$this->assign('type',$type);
		}
		if($shop_name){
			$map['shop_name'] = array('like',"%$shop_name%");
			$this->assign('shop_name',$shop_name);
		}
		// 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
		// 选择U店
		$map['s.founder_id'] = array('gt',0);
		import('ORG.Util.Page'); // 导入分页类
        $count = $this->shop->alias('s')
        		->join('uboss_founder f on f.founder_id = s.founder_id')
        		->join('uboss_users u on u.user_id = f.user_id')
				->field('s.*,f.store_type,u.realname')
				->where($map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $shop = $this->shop->alias('s')
        		->join('uboss_founder f on f.founder_id = s.founder_id')
        		->join('uboss_users u on u.user_id = f.user_id')
				->field('s.*,f.store_type,u.realname')
				->where($map)
				->limit($Page->firstRow . ',' . $Page->listRows)
				->select();
		foreach ($shop as $key => $value){
			$res = M('shop_cate')->where('cate_id = '.$value['cate_id'])->field('cate_name')->find();
			$shop[$key]['cate_name'] = $res['cate_name'];
			// 城市
			$city = $this->city
					->where('city_id = '.$value['city_id'])
					->field('name')
					->find();
			$shop[$key]['city'] = $city['name'];
			// 区域
			$area = $this->area
					->where('area_id = '.$value['area_id'])
					->field('area_name')
					->find();
			$shop[$key]['area'] = $area['area_name'];
			switch ($shop[$key]['store_type']) {
				case 1:
					$zj = 50;
					$jl = 100;
					$zg = 150;
					
				break;
				case 2:
					$zj = 20;
					$jl = 30;
					$zg = 50;
					break;
				case 3:
					$zj = 5;
					$jl = 10;
					$zg = 20;
					break;
				default:
					# code...
					break;
			}
			// 总监
			$shop[$key]['zj'] = ($this->apply
								->where('shop_id = '.$value['shop_id'].' and apply_position = 1 and status = 2')
								->count()).'/'.$zj;
			// 经理
			$shop[$key]['jl'] = ($this->apply
								->where('shop_id = '.$value['shop_id'].' and apply_position = 2 and status = 2')
								->count()).'/'.$jl;
			// 主管
			$shop[$key]['zg'] = ($this->apply
								->where('shop_id = '.$value['shop_id'].' and apply_position = 3 and status = 2')
								->count()).'/'.$zg;
		}
		$this->assign('list',$shop);
		$this->assign('count', $count);
		$this->assign('page', $show); // 赋值分页输出
		$this->display();
	}
	// U店构成人数
	public function consist()
	{
		$shop_id = intval($_GET['shop_id']);
		$map['shop_id'] = $shop_id;
		$type = intval($_GET['type']) ? intval($_GET['type']) : 0;
		if($type){
			$map['apply_position'] = $type;
		}
		
		 // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
		$data = $this->apply->where($map)->select();
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
			if(empty($shop)){
				unset($data[$key]);
			}
			$data[$key]['shop_name'] = $shop['shop_name'];
		}
		$this->assign('list',$data);
		$this->display();
	}
	public function divide_money()
	{
		$this->display();
	}
	// U店详情
	public function ulist()
	{
		$this->display();
	}
	// 发放分红
	public function give_money()
	{
		$shop_id = $this->_param('shop_id');
		$money = $this->_param('money');
		// 人气店 股份10万
		// 创始人
		$founder = $money*19;
		$data['money'] = $founder;
		$data['shop_id'] = $shop_id;
		$data['user_id'] = M('founder')->where('shop_id = '.$shop_id)->getField('user_id');
		$data['intro'] = '创始人U店分红';
		$data['status'] = 2;
		$data['type'] = 4;
		$data['create_time'] = time();
		$data['create_ip'] = get_client_ip();
		M('user_money_logs')->add($data);
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
			$r = M('user_money_logs')->add($user);
			if($r){
				// 用户余额增加
				$money = M('users')->where('user_id = '.$value['user_id'])->getField('money');
				$money['money'] = floor($money) + $user['money'];
				M('users')->where('user_id = '.$value['user_id'])->save($money);
			}
		}
		$this->baoSuccess('发放成功',U('index'));
	}
}
