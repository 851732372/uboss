<?php

class FansAction extends CommonAction {

	public function index() {
		import('ORG.Util.Page'); // 导入分页类
		$map = array('a.shop_id' => $this->shop_id); //查询条件	
		$count = D('ShopFootprint a')->where($map)->count(); // 查询满足要求的总记录数 
		$Page = new Page($count, 3); // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show(); // 分页显示输出
		$map['a.shop_id'] = $this->shop_id;
		$where['a.closed'] = 0;
        $list = D('ShopFootprint a')
            ->join('uboss_users s ON s.user_id = a.user_id')
            ->where($map)
            ->field('s.*,a.create_time ctime')
            ->order('a.create_time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
		$this->assign('list', $list); // 赋值数据集
		$this->assign('page', $show); // 赋值分页输出
		$this->display(); // 输出模板
	}

	public function add($user_id=0) {
		$fans=D('Shopfavorites');
		$uid=(int)($user_id);
		$user = D('Users')->find($user_id);
		$shop=D('shop')->find($this->shop_id);
		if ($this->isPost()){
			$integral=(int)($_POST['integral']);
			if($integral <= 0){
				$this->baoError('请输入正确的积分');
			}
			if($this->member['integral'] < $integral){
				$this->baoError('您的账户积分不足');
			}
			D('Users')->addIntegral($this->uid,-$integral,'赠送会员积分');
			D('Users')->addIntegral($user_id,$integral,'获得商家赠送积分');
			$this->baoSuccess('赠送积分成功!',U('fans/add',array('user_id'=>$user_id)));
		} else {
			$this->assign('shop', $shop);
			$this->assign('jifen',$this->member['integral']);
			$this->assign('user', $user);
			$this->display();
		}
	}
}
