<?php
/*
 * 提现管理
 * 作者：liuqiang
 * 日期: 2018/9/28
 */
class ShopfinanceAction extends CommonAction {
	public function _initialize()
	{
		parent::_initialize();
		$this->shop_money_logs = M('shop_money_logs');
		$this->shop = M('shop');
		$this->ex = M('users_ex');
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

		$map['l.type'] = 1;
		
		$shop = $this->shop_money_logs->alias('l')
				->join('uboss_shop s on s.shop_id = l.shop_id')
				->where($map)
				->order('l.create_time desc')
				->field('l.*')
				->select();
		foreach ($shop as $key => $value) {
			$shop[$key]['username'] = $this->shop->where('shop_id = '.$value['shop_id'])->getField('shop_name');
			$shop[$key]['ymoney'] = $this->shop->where('shop_id = '.$value['shop_id'])->getField('money');
			$shop[$key]['bank_num'] = $this->ex->where('shop_id = '.$value['shop_id'])->getField('bank_num');
			$shop[$key]['tel'] = $this->ex->where('shop_id = '.$value['shop_id'])->getField('tel');
		}
		$this->assign('shop',$shop);
		$this->display();
	}
	// 审核
	public function ok()
	{	
		$this->shop->startTrans();
		$data['shop_log_id'] = $this->_param('shop_log_id');
		$data['status'] = 2;
		$data['ok_time'] = time();
		$r = $this->shop_money_logs->save($data);
		if($r){
			$shop_id = $this->shop_money_logs->where('shop_log_id = '.$data['shop_log_id'])->getField('shop_id');
			$smoney = $this->shop_money_logs->where('shop_log_id = '.$data['shop_log_id'])->getField('money');
			$money = $this->shop->where('shop_id = '.$shop_id)->getField('money');
			$money -= $smoney;
			$arr['shop_id'] = $shop_id;
			$arr['money'] = $money;
			$res = $this->shop->save($arr);
			if($res){
				$this->shop->commit();
			}else{
				$this->shop->rollback();
			}
		}
		$this->baoSuccess('审核通过',U('index'));
	}
	public function refuse()
	{
		if(IS_POST){
			$data['shop_log_id'] = $this->_param('shop_log_id');
			$data['status'] = 1;
			$data['reason'] = $this->_param('reason');
			$data['ok_time'] = time();
			$this->shop_money_logs->save($data);
			$this->baoSuccess('审核不通过',U('index'));
		}else{
			$this->display();
		}
	}
	public function refuse_cause()
	{
		$shop_log_id = $this->_param('shop_log_id');
		$reason = $this->shop_money_logs->where('shop_log_id = '.$shop_log_id)->getField('reason');
		$this->assign('reason',$reason);
		$this->display();
	}
	// 流水
	public function account()
	{
		// 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
		$audit = $this->_param('audit');
		$shop_name = $this->_param('shop_name');
		if($audit){
			switch ($audit) {
				case 1:
					$map['l.audit'] = $audit;
					break;
				case 0:
					$map['l.audit'] = $audit;
					break;
				default:
					# code...
					break;
			}
			$this->assign('audit',$audit);
		}
		if($shop_name){
			$map['s.shop_name'] = array('like',"%$shop_name%");
			$this->assign('shop_name',$shop_name);
		}
		$map['l.type'] = "2";
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->shop_money_logs->alias('l')
        		->join('uboss_shop s on s.shop_id = l.shop_id')
        		->where($map)
        		->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 16); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $this->shop_money_logs->alias('l')
        		->join('uboss_shop s on s.shop_id = l.shop_id')
        		->where($map)
        		->order(array('s.create_time' => 'desc'))
        		->field('l.*,s.shop_name,s.proportions,s.money smoney,s.tel')
        		->limit($Page->firstRow . ',' . $Page->listRows)
        		->select();
        foreach ($list as $key => $value) {
            $list[$key]['p_price'] = $value['money']*$value['proportions']/100;
            $list[$key]['s_price'] = M('order')->where('order_id = '.$value['order_id'])->getField('total_price');
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('count', $count); // 赋值分页输出
        $this->display();
	}
}
