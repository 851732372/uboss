<?php
/*
 * 珊瑚首页信息
 * 作者：liuqiang
 * 日期: 2018/9/17
 */

class IndexAction extends CommonAction {
    public function _initialize()
    {
        parent::_initialize();
        $this->order = M('order');
        $this->order_goods = M('order_goods');
        $this->comment = M('comment');
        $this->shop = M('shop');
    }
    public function index() {
        $this->display();
    }

    public function main() {
        // 今日收入
        $bg_time = strtotime(TODAY);
        $all_money = $this->order->where(array(
                    'shop_id' => $this->shop_id,
                    'create_time' => array(
                        array('ELT', NOW_TIME),
                        array('EGT', $bg_time),
                    ), 'status' => array(
                        'eq', 4
                    ),
                ))->sum('total_price');
        $this->assign('all_money',$all_money/100);

        // 今日销量
        $today_num = $this->order_goods
                    ->where('shop_id = '.$this->shop_id)
                    ->sum('num');
        $this->assign('today_num',$today_num);
        // 评论数量
        $comment_num = $this->comment
                       ->where('shop_id = '.$this->shop_id)
                       ->count();
        $this->assign('comment_num',$comment_num);
        // 客户
        $custorm = $this->order
                   ->where('shop_id = '.$this->shop_id)
                   ->group('user_id')
                   ->count();
        $this->assign('custorm',$custorm);
        // 待消费
        $consumed = $this->order
                    ->where('shop_id = '.$this->shop_id.' and status = 6')
                    ->count();
        $this->assign('consumed',$consumed);
         // 待付款
        $obligation = $this->order
                    ->where('shop_id = '.$this->shop_id.' and status = 6')
                    ->count();
        $this->assign('obligation',$obligation);
        // 已付款
        $paid = $this->order
                ->where('shop_id = '.$this->shop_id.' and status = 4')
                ->count();
        $this->assign('paid',$paid);
        // 待评价
        $evaluated = $this->order
                ->where('shop_id = '.$this->shop_id.' and status = 7')
                ->count();
        $this->assign('evaluated',$evaluated);   
        // 已完成
        $completed = $this->order
                    ->where('shop_id = '.$this->shop_id.' and status = 8')
                    ->count();
        $this->assign('completed',$completed);  
        // 退款中
        $refunding = $this->order
                    ->where('shop_id = '.$this->shop_id.' and status = 9')
                    ->count();
        $this->assign('refunding',$refunding);
        // 资金管理
        $money = $this->shop
                ->where('shop_id = '.$this->shop_id)
                ->field('asset')
                ->find();
        $this->assign('money',$money);
        // 订单数量
        $order_num = $this->order
                    ->where('shop_id = '.$this->shop_id)
                    ->count();
        $this->assign('order_num',$order_num);
        $this->display();
    }
    public function ajax_select_d(){
        $begin = mktime(0,0,0,date('m'),1,date('Y'));
        $end = mktime(23,59,59,date('m'),date('t'),date('Y'));
        $map['_string'] = "(create_time >= $begin and create_time <= $end and status > 4 and status !=5)";
        $map['shop_id'] = $this->shop_id;
        $res = M('order')
            ->where($map)
            ->order('create_time asc')
            ->group('FROM_UNIXTIME(create_time,"%m-%d")')
            ->field('FROM_UNIXTIME(create_time,"%m-%d") date,count(*) num')
            ->select();
        echo outJson($res);
    }

}
