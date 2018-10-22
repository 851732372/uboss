<?php
/*
 * 订单管理
 * 作者：liuqiang
 * 日期: 2018/9/20
 */
class OrdersAction extends CommonAction {
    public function index(){
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
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['orderno'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        $map['status'] = $this->_param('status') ? $this->_param('status') : 1;
        if($map['status'] == 4){
            $map['_string'] = "status = 4 or status = 6";
            unset($map['status']);
        }
        $count = $Order->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Order->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
                ->field('u.nickname,u.mobile,o.*,u.level_id')
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
    // 审核
    public function ajax_refuse()
    {
        $id = intval($_POST['id']);
        $arr['status'] = 10;
        $r  = M('order')->where('order_id = '.$id)->save($arr);
        echo $r;
    }
    public function ajax_ok()
    {
        $id = intval($_POST['id']);
        $arr['status'] = 11;
        $r  = M('order')->where('order_id = '.$id)->save($arr);
        echo $r;
    }
}
