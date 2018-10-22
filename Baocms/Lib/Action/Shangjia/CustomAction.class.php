<?php
/*
 * 客户管理
 * 作者：liuqiang
 * 日期: 2018/9/15
 */
class CustomAction extends CommonAction {
    public $sql;
    public function _initialize()
    {
        parent::_initialize();
        $this->order_goods = M('order_goods');
        $this->user = M('users');
        $this->order = M('order');
        $this->comment = M('comment');
        $this->addr = M('user_addr');
        $this->goods = M('goods');
    }
    // 我的客户
    public function index()
    { 
        if($_POST['nickname']){
            $map['u.nickname'] = array('like',"%{$_POST['nickname']}%");
            $this->assign('nickname',$_POST['nickname']);
        }

        if($_POST['tel']){
            $map['u.mobile'] = $_POST['tel'];
            $this->assign('tel',$_POST['mobile']);
        }

        if($_POST['start_date'] && $_POST['end_date']){
            $begin = strtotime($_POST['start_date']);
            $end = strtotime($_POST['end_date']);
            $map['_string'] = "(o.create_time >= $begin AND o.create_time <= $end)";
            $this->assign('start_date',$_POST['start_date']);
            $this->assign('end_date',$_POST['end_date']);
        }

        if($_POST['start_date'] && !$_POST['end_date']){
            $map['o.create_time'] = array('egt',strtotime($_POST['start_date']));
             $this->assign('start_data',$_POST['start_date']);
        }

        if($_POST['end_date'] && !$_POST['start_date']){
            $map['o.create_time'] = array('elt',strtotime($_POST['end_date']));
            $this->assign('end_date',$_POST['end_date']);
        }
        $map['o.shop_id'] = $this->shop_id;
        $map['o.status'] = array('gt',0);
        $data = $this->user->alias('u')
                ->join('uboss_order o on o.user_id = u.user_id')
                ->field('u.user_id,u.nickname,u.level_id,u.mobile,u.face,sum(o.total_price) total_price,o.create_time,count(*) num')
                ->group('u.user_id')
                ->where($map)
                ->select();
        // foreach ($data as $key => $value) {
        //     $newData[$value['user_id']] = $value;
        //     $order_list = $this->order
        //             ->where('user_id = '.$value['user_id'])
        //             ->field('order_id')
        //             ->select();
        //     foreach ($order_list as $key1 => $value1) {
        //         $goods[] = $this->order_goods
        //                 ->where('order_id = '.$value1['order_id'])
        //                 ->count().'-'.$value['user_id'];
        //     }
        //     // 订单总数
        //     $onum = array();
        //     foreach ($goods as $key2 => $value2) {
        //         $arr = explode('-', $value2);
        //         $onum[$arr[1]][] = $arr[0];
        //     }
        //     foreach ($onum as $key4 => $value4) {
        //         $newData[$key4]['num'] = array_sum($onum[$key4]);
        //     }
        //     // 时间
        //     $time = $this->order
        //             ->where('user_id = '.$value['user_id'])
        //             ->field('max(create_time) create_time')
        //             ->find();
        //     $newData[$key4]['create_time'] = $time['create_time'];
        // }
        $this->assign('data',$data);
        $this->display();
    }
    public function look_info()
    {
        import('ORG.Util.Page'); // 导入分页类
        $user_id = intval($_GET['user_id']);
        // 用户信息
        $user = $this->user
                ->find($user_id);
        $this->assign('user',$user);
        // 消费金额
        // 已付款
        $money = $this->order
                ->where('user_id = '.$user_id.' and status = 4'.' and shop_id = '.$this->shop_id)
                ->sum('total_price');
        // 退货
        $bmoney = $this->order
                ->where('user_id = '.$user_id.' and status = 11'.' and shop_id = '.$this->shop_id)
                ->sum('total_price');  
        $money -= $bmoney;   
        $this->assign('money',$money/100);
        // 订单数量
        $onum = $this->order
                ->where('user_id = '.$user_id.' and status > 0'.' and shop_id = '.$this->shop_id)
                ->count();
        $this->assign('onum',$onum);
        // 退货
        $order = $this->order
                ->where('user_id = '.$user_id.' and shop_id = '.$this->shop_id)
                ->field('order_id')
                ->select();
        foreach ($order as $key => $value) {
            $res[] = $this->order_goods
                    ->where('order_id = '.$value['order_id'].' and status = 11')
                    ->count();
        }
        $back = array_sum($res);
        $this->assign('back',$back);
        // 评价记录
        $com = $this->comment
                ->where('user_id = '.$user_id.' and shop_id = '.$this->shop_id)
                ->count();
        $this->assign('com',$com);
        // 订单
        $count = $this->order->alias('o')
                ->join('uboss_user_addr a on a.addr_id = o.addr_id')
                ->where('shop_id = '.$this->shop_id.' and o.user_id = '.$user_id)
                ->count();
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $order = $this->order->alias('o')
                ->join('uboss_user_addr a on a.addr_id = o.addr_id')
                ->where('shop_id = '.$this->shop_id.' and o.user_id = '.$user_id)
                ->field('o.*,a.name,a.mobile')
                ->order('o.create_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('order',$order);
        $this->assign('page',$show);
        $this->display();
    }
    // 订单商品
    public function goods_info()
    {
        $order_id = intval($_GET['order_id']);
        $data = $this->order_goods->alias('o')
                ->where('order_id = '.$order_id)
                ->find();
        $dat = $this->order->alias('o')
                ->where('order_id = '.$order_id)
                ->field('status,create_time')
                ->find();
        $goods = $this->goods
                ->find($data['goods_id']);
        $goods['num'] = $data['num'];
        $goods['total'] = $data['total_price'];
        $goods['status'] = $dat['status'];
        $goods['time'] = $dat['create_time'];
        $this->assign('goods',$goods);
        $this->display();
    }
}