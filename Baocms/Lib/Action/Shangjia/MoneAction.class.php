<?php
/*
 * 资金管理
 * 作者：liuqiang
 * 日期: 2018/9/18
 */
class MoneAction extends CommonAction 
{
	public function _initialize()
	{
		parent::_initialize();
        $this->shop_money_logs = M('shop_money_logs');
	}
	public function index(){    
        $map = array('shop_id' => $this->shop_id);    
        if (($bg_date = $this->_param('bg_date', 'htmlspecialchars') ) && ($end_date = $this->_param('end_date', 'htmlspecialchars'))) {
            $bg_time = strtotime($bg_date);
            $end_time = strtotime($end_date);
            $map['create_time'] = array(array('ELT', $end_time), array('EGT', $bg_time));
            $this->assign('bg_date', $bg_date);
            $this->assign('end_date', $end_date);
        } else {
            if ($bg_date = $this->_param('bg_date', 'htmlspecialchars')) {
                $bg_time = strtotime($bg_date);
                $this->assign('bg_date', $bg_date);
                $map['create_time'] = array('EGT', $bg_time);
            }
            if ($end_date = $this->_param('end_date', 'htmlspecialchars')) {
                $end_time = strtotime($end_date);
                $this->assign('end_date', $end_date);
                $map['create_time'] = array('ELT', $end_time);
            }
        }
        $map['_string'] = "status = 2 or audit = 1";
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->shop_money_logs->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $this->shop_money_logs
        		->where($map)
        		->order(array('create_time' => 'desc'))
        		->limit($Page->firstRow . ',' . $Page->listRows)
        		->select();
        $arr = array();
        foreach ($list as $key => $value) {
            if($value['type'] == 1){
                $arr[$key]['type'] = '提现';
                $mone1 += $value['money'];
            }
            if($value['type'] == 2 && $value['audit'] == 1){
                $arr[$key]['type'] = '收入【已入账】';
                $mone2 += $value['money'];
            }
            if($value['type'] == 2 && $value['audit'] == 0){
                $arr[$key]['type'] = '收入【未入账】';
                $mone3 += $value['money'];
            }
            $money = floor($value['money'])/100;
            $arr[$key]['money'] = $money."元";
            $arr[$key]['intro'] = $value['intro'];
            $arr[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
        }
        $this->assign('mone1',$mone1);
        $this->assign('mone2',$mone2);
        $this->assign('mone3',$mone3);
        $this->assign('list', $arr); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    // 提现
    public function tixianlog()
    {    
        if (($bg_date = $this->_param('bg_date', 'htmlspecialchars') ) && ($end_date = $this->_param('end_date', 'htmlspecialchars'))) {
            $bg_time = strtotime($bg_date);
            $end_time = strtotime($end_date);
            $map['create_time'] = array(array('ELT', $end_time), array('EGT', $bg_time));
            $this->assign('bg_date', $bg_date);
            $this->assign('end_date', $end_date);
        } else {
            if ($bg_date = $this->_param('bg_date', 'htmlspecialchars')) {
                $bg_time = strtotime($bg_date);
                $this->assign('bg_date', $bg_date);
                $map['create_time'] = array('EGT', $bg_time);
            }
            if ($end_date = $this->_param('end_date', 'htmlspecialchars')) {
                $end_time = strtotime($end_date);
                $this->assign('end_date', $end_date);
                $map['create_time'] = array('ELT', $end_time);
            }
        }
        $map['type'] = 1;
        $map['shop_id'] = $this->shop_id;
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->shop_money_logs->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 16); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $this->shop_money_logs
        		->where($map)
        		->order('create_time desc')
        		->limit($Page->firstRow . ',' . $Page->listRows)
        		->select();
        foreach ($list as $key => $value) {
            $list[$key]['bank_no'] = D('Usersex')->where('shop_id = '.$value['shop_id'])->getField('bank_num');
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    // 提现
    public function tixian()
    {
        if (IS_POST)
        {
            
            if(!$_POST['bank_realname'] || !$_POST['bank_num']){
                $this->baoError('请先填写提现账号信息');
            }
        	$audit = M('shop')->where('shop_id = '.$this->shop_id)->getField('audit');
	     	if($audit != 2 ){
	     		$this->baoError('认证通过后才可以提现'); 
	     	}
            $money = (int) ($_POST['money'] * 100);
            if ($money <= 0)
            {
                $this->baoError('提现金额不合法');
            }
            if ($money > $this->member['money'] || $this->member['money'] == 0)
            {
                $this->baoError('余额不足，无法提现');
            }
            $arr['type'] = 1;
            $arr['shop_id'] = $this->shop_id;
            $arr['ex_id'] = D('Usersex')->where('shop_id = '.$this->shop_id)->getField('ex_id');
            $arr['money'] = $money;
            $money = floor($money)/100;
            $arr['intro'] = '商户申请提现，扣款'.$money."元";
            $arr['create_ip'] = get_client_ip();
            $arr['create_time'] = time();
            $this->shop_money_logs->add($arr);
            $this->baoSuccess('正在审核中', U('mone/index'));
        }else{
            $this->assign('info',D('Usersex')->where('shop_id = '.$this->shop_id)->find());
            $this->assign('money', $this->member['money']);

            $this->display();
        }
    }
    // 对账单
    // public function account()
    // {
    //     if (($bg_date = $this->_param('bg_date', 'htmlspecialchars') ) && ($end_date = $this->_param('end_date', 'htmlspecialchars'))) {
    //         $bg_time = strtotime($bg_date);
    //         $end_time = strtotime($end_date);
    //         $map['create_time'] = array(array('ELT', $end_time), array('EGT', $bg_time));
    //         $this->assign('bg_date', $bg_date);
    //         $this->assign('end_date', $end_date);
    //     } else {
    //         if ($bg_date = $this->_param('bg_date', 'htmlspecialchars')) {
    //             $bg_time = strtotime($bg_date);
    //             $this->assign('bg_date', $bg_date);
    //             $map['create_time'] = array('EGT', $bg_time);
    //         }
    //         if ($end_date = $this->_param('end_date', 'htmlspecialchars')) {
    //             $end_time = strtotime($end_date);
    //             $this->assign('end_date', $end_date);
    //             $map['create_time'] = array('ELT', $end_time);
    //         }
    //     }
    //     $map['type'] =  $this->_param('type') ? $this->_param('type') : 1;
    //     $map['status'] = 8;
    //     $data = M('order')
    //             ->where($map)
    //             ->group('FROM_UNIXTIME(create_time,"%Y-%m-%d")')
    //             ->field('FROM_UNIXTIME(create_time,"%Y-%m-%d") date,sum(total_price) price')
    //             ->order('create_time desc')
    //             ->select();
    //     foreach ($data as $key => $value) {
    //         $data[$key]['z_price'] = $value['price']*$this->member['proportions']/100;
    //         $data[$key]['s_price'] = $value['price'] -  $data[$key]['z_price'];
    //     }
    //     $this->assign('list',$data);
    //     $this->display();
    // }
    /**
        只有线上对账单 其他类型对账单需要订单有类型
    */
    public function account()
    {
        if (($bg_date = $this->_param('bg_date', 'htmlspecialchars') ) && ($end_date = $this->_param('end_date', 'htmlspecialchars'))) {
            $bg_time = strtotime($bg_date);
            $end_time = strtotime($end_date);
            $map['create_time'] = array(array('ELT', $end_time), array('EGT', $bg_time));
            $this->assign('bg_date', $bg_date);
            $this->assign('end_date', $end_date);
        } else {
            if ($bg_date = $this->_param('bg_date', 'htmlspecialchars')) {
                $bg_time = strtotime($bg_date);
                $this->assign('bg_date', $bg_date);
                $map['create_time'] = array('EGT', $bg_time);
            }
            if ($end_date = $this->_param('end_date', 'htmlspecialchars')) {
                $end_time = strtotime($end_date);
                $this->assign('end_date', $end_date);
                $map['create_time'] = array('ELT', $end_time);
            }
        }
        $map['type'] = 2;
        $map['shop_id'] = $this->shop_id;
        $data = $this->shop_money_logs
                ->where($map)
                ->select();
        $this->assign('list',$data);
        $this->display();

    }
}