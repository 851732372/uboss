<?php

/*
 * 消费券管理
 * 作者：liuqiang
 * 日期: 2018/9/20
 */
class CouponAction extends CommonAction{
    
    public function index() {
        
        $Order = D('Order');
        import('ORG.Util.Page'); // 导入分页类
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
        if($mobile = $this->_param('mobile')){
            $map['u.mobile'] = $mobile;
            $this->assign('mobile', $mobile);
        }
        if($coupon = $this->_param('coupon')){
            $map['o.coupon'] = $coupon;
            $this->assign('coupon', $coupon);
        }
        $map['_string'] = "status = 4 or status = 6 or status = 7";
        $count = $Order->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Order->alias('o')
                ->join('uboss_users u on u.user_id = o.user_id')
                ->where($map)
                ->order(array('o.order_id' => 'desc'))
                ->field('o.*,u.nickname,u.mobile')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        
        foreach ($list as $key => $value) {
            $goods = M('order_goods')->alias('o')
                    ->join('uboss_goods g on o.goods_id = g.goods_id')
                    ->field('g.title,o.num,o.total_price')
                    ->where('order_id = '.$value['order_id'])
                    ->select();
            foreach ($goods as $key1 => $value1) {
                $list[$key]['goodsname'] = $value1['title'];
                $list[$key]['num'] = $value1['num'];
                $list[$key]['total'] = $value1['total_price'];
            }
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('count', $count);
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
    public function info()
    {
        $map = array('shop_id' => $this->shop_id);
        $map['order_id'] = $this->_param('order_id');
        $list = M('order')->alias('o')
                ->join('uboss_users u on o.user_id = u.user_id')
                ->field('u.nickname,u.mobile,o.*')
                ->where($map)
                ->find();
        $order_goods = M('order_goods')->where('order_id = '.$list['order_id'])->find();
        $list['num'] = $order_goods['num'];
        $list['total'] = $order_goods['total_price'];

        $goods = M('goods')->field('title')->find($order_goods['goods_id']);
        $list['title'] = $goods['title'];

        $this->assign('shopname',$this->member['shop_name']);

        $this->assign('list',$list);
        $this->display();
    }
    // 核销
    public function ajax_verify()
    {
        $id = $_POST['id'];
        $arr['status'] = 7;
        echo $r = M('order')->where('order_id = '.$id)->save($arr);
    }
    // 验证
    public function ajax_coupon()
    {

    }
}