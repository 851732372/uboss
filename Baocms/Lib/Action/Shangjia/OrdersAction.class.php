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
        $this->assign('status',$map['status']);
        if($map['status'] == 4){
            $map['_string'] = "status = 4 or status = 6 or status = 7 or status = 8 or status = 12";
            unset($map['status']);
        }
        if($map['status'] == 8){
            $map['_string'] = "status = 8 or status = 12";
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
        $map['order_id'] = $this->_param('order_id');
        $status = $this->_param('status');
        if($status == 9 || $status == 10 || $status == 11){
            $back = M('back_goods')
                    ->where($map)
                    ->find();
            $this->assign('back',$back);
        }
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
        M('order')->where('order_id = '.$id)->save($arr);
        $data['status'] = 0;
        $data['ok_time'] = time();
        M('back_goods')->where('order_id = '.$id)->save($data);
    }
    public function ajax_ok()
    {
        // 修改订单状态 已退款
        $id = intval($_POST['id']);
        $arr['status'] = 11;
        M('order')->where('order_id = '.$id)->save($arr);
        // 退款表信息修改
        $data['status'] = 2;
        $data['ok_time'] = time();
        M('back_goods')->where('order_id = '.$id)->save($data);
        
        // 订单信息
        $order = M('order')->find($id);
        // 订单交易号
        $out_trade_no = M('payment_logs')->where('order_id = '.$id)->getField('log_id');
        switch ($order['trade_style']) {
            // 支付宝
            case 1:
                 $api_params = [
                   'out_trade_no'  => $out_trade_no,//商户订单号 和支付宝交易号trade_no 二选一
                   'refund_amount'  => $order['total_price'], //退款金额
                   'out_request_no'  =>  md5(time()),  //退款唯一标识  部分退款时必传
                ];
                echo $this->alipay_refund($api_params);
                break;
            // 微信
            case 2:
                $data = [
                    'out_trade_no'     => $out_trade_no, //订单交易号
                    'out_refund_no'    => md5(time()), //退款单号
                    'total_fee'        => $order['total_price'], //原订单金额
                    'refund_fee'       => $order['total_price'] //退款金额
                ];
                echo $this->wx_refund($data);
                break;
              // 余额
            case 3:
                // 审核通过 插入到余额变动表 user_money_logs type=7  同时余额变动
                // 订单信息
                $order = M('user_money_logs')->where('order_id = '.$id)->find();
                unset($order['log_id']);
                unset($order['create_time']);
                $order['create_time'] = time();
                $order['type'] = 7;
                M('user_money_logs')->add($order);
                // 余额变动
                $money = M('users')->where('user_id = '.$order['user_id'])->getField('money');
                $money += $order['money'];
                M('users')->where('user_id = '.$order['user_id'])->save(array('money'=>$money));
                break;
              // 资产支付
            case 4:
                // 审核通过 插入到变动表 user_money_logs type=7  同时余额变动
                // 订单信息
                $order = M('user_asset_logs')->where('order_id = '.$id)->find();
                unset($order['asset_id']);
                unset($order['create_time']);
                $order['type'] = 6;
                $order['create_time'] = time();
                M('user_asset_logs')->add($order);
                // 余额变动
                $money = M('users')->where('user_id = '.$order['user_id'])->getField('asset');
                $money += $order['money'];
                M('users')->where('user_id = '.$order['user_id'])->save(array('asset'=>$money));
                break;
            default:
                # code...
                break;
        }
    }
    // 微信退款
    public function wx_refund($data)
    {
        $obj = new WxpayrefundAction($data);
        $res = $obj->orderRefund();
        $msg = $obj->XmlToArr($res);
        if('SUCCESS' == $msg['return_code'] && 'OK' == $msg['return_msg']){
            $order_id = M('payment_logs')->where('log_id = '.$msg['out_trade_no'])->getField('order_id');
            // 插入退单号
            $refundno = $msg['out_refund_no'];
            M('back_goods')->where('order_id = '.$order_id)->save('refundno = '.$refundno);
            return 'SUCCESS';
        }else{
            return 'FAIL';
        }
    }
    // 支付宝退款
    public function alipay_refund($api_params)
    {
        $obj = new AlipayrefundAction();
        //老版
        // $detail_data = "2018012521001004500506922700^0.01^测试第一笔退款";
        //$obj->oldRefund(1, $detail_data);
        //新版
        $r = $obj->newrefund($api_params);
        echo '<pre>';
        print_r($r);
        echo '</pre>';
    }
}

