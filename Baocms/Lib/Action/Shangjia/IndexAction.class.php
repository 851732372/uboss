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
        $this->shop_money_logs = M('shop_money_logs');
    }
    public function index() {
        $cate_id = M('shop')->where('shop_id = '.$this->shop_id)->getField('cate_id');
         // 酒店
        $pid = 12;
        $hotel = M('shop_cate')->where('parent_id = '.$pid)->field('cate_id')->select();
        foreach ($hotel as $key => $value) {
            $hotel_id[] = $value['cate_id'];
        }
        array_push($hotel_id,12);
        if(in_array($cate_id,$hotel_id)){
            $is_enough = M('shop')->where('shop_id = '.$this->shop_id)->getField('is_enough');
            $hotel = 1;
            $this->assign('is_enough',$is_enough);
            $this->assign('hotel',$hotel);
        }
        $this->display();
    }
    // 酒店
    public function hotel()
    {
        $data['shop_id'] = $this->shop_id;
        $data['is_enough'] = 1;
        M('shop')->save($data);
        $this->baoSuccess('设置成功',U('index'));
    }
    public function hotels()
    {
        $data['shop_id'] = $this->shop_id;
        $data['is_enough'] = 0;
        M('shop')->save($data);
        $this->baoSuccess('设置成功',U('index'));
    }
    public function main() {
        $bg_time = strtotime(TODAY);
         // 总收入
        $allmoney = $this->shop_money_logs->where('shop_id = '.$this->shop_id)
                    ->field('sum(money) price')
                    ->find();
        $money = floor($allmoney['price'])/100;
        $this->assign('all_money',$money);

        // 今日销量
        $today_num = $this->order_goods
                            ->where(
                                array(
                                    'shop_id' => $this->shop_id,
                                    'create_time' => array(
                                        array('ELT', NOW_TIME),
                                        array('EGT', $bg_time)
                                    )
                                )
                            )->sum('num');
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
                    ->where('shop_id = '.$this->shop_id.' and status = 6 or status = 4')
                    ->count();
        $this->assign('consumed',$consumed);
         // 待付款
        $obligation = $this->order
                    ->where('shop_id = '.$this->shop_id.' and status = 1')
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
                    ->where('shop_id = '.$this->shop_id.' and status = 8 or status = 12')
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
        $map['_string'] = "(create_time >= $begin and create_time <= $end)";
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
