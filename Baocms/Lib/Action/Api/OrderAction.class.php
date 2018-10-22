<?php

/**
 * File name: OrderAction.php
 * 订单类
 * Created on: 2018/9/22 13:51
 * Created by: Ginger.
 */
class OrderAction extends BaseAction
{
    protected $orderStatus = array();
    protected $refunAccount = array(1 => '支付宝', 2 => '微信', 3 => '余额', 4 => '资产');
    public function order()
    {
        if ($this->isPost())
        {
            $type = $this->_post('type');
            if ($type == 'createOrder')    //  创建订单
            {
                $this->add();
            }
            elseif ($type == 'payOrder')    //  支付订单
            {
                $this->pay();
            }
            elseif ($type == 'delOrder')    //  删除订单
            {
                $this->del();
            }
            elseif ($type == 'list')    //  订单列表
            {
                $this->lists();
            }
            elseif ($type == 'detail')  //  订单详情
            {
                $this->detail();
            }
            elseif ($type == 'refund')  //  申请退款
            {
                $this->refund();
            }
            elseif ($type == 'refundStatus')  //  退款进度
            {
                $this->refundStatus();
            }
            else
            {
                return outMessage(-1,'非法请求，参数错误');
            }
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }

    private function add()
    {
        if ($this->isPost())
        {
            $num = (int) $this->_post('num');
            $goodsId = (int) $this->_post('id');
            $addressId = (int) $this->_post('addressId');
            $bookPeople = $this->_post('bookPeople');
            $bookTel = $this->_post('bookTel');
            $residenceTime = $this->_post('residenceTime');
            $ip = get_client_ip();

            if (empty($goodsId)) return outMessage(-1, '请选择正确的商品');
            if (empty($num)) return outMessage(-1, '很抱歉请填写正确的购买数量');
            $goods = D('Goods')->find($goodsId);
            if (empty($goods)) return outMessage(-1, '很抱歉，您提交的产品暂时不能购买！');

            $shopProportions = D('Shop')->get_proportions($goods['shop_id']);
            $price = $goods['mall_price'] * $num;
            $totalPrice = mPrice($price, $shopProportions, session('userInfo.level_id'));
            $orderGoods = array(
                'goods_id' => $goods['goods_id'],
                'shop_id' => $goods['shop_id'],
                'num' => $num,
                'price' => $goods['mall_price'],
                'total_price' => $totalPrice,
//                    'mobile_fan'=>$mobile_fan,
                'js_price' => $totalPrice,
                'create_time' => NOW_TIME,
                'create_ip' => $ip
            );
            //总订单
            $coupon = createCoupon();
            if (D('Order')->where(array('coupon' => $coupon))->find()) $coupon = createCoupon();
            $order = array(
                'user_id' => session('userInfo.user_id'),
                'shop_id' => $goods['shop_id'],
                'create_time' => NOW_TIME,
                'create_ip' => $ip,
                'total_price' => $totalPrice,
                'original_price' => $price,
                'precent' => $shopProportions,
                'orderno' => createNo(),
                'coupon' => $coupon,
                'addr_id' => $addressId,
                'status' => 1,
                'bookinfo' => json_encode(array('bookPeople' =>$bookPeople, 'bookTel' => $bookTel, 'residenceTime' => $residenceTime))
            );

//            $tui = cookie('tui');
//            if (!empty($tui)) {//推广部分
//                $tui = explode('_', $tui);
//                $tuiguang = array(
//                    'uid' => (int) $tui[0],
//                    'goods_id' => (int) $tui[1]
//                );
//            }
            $shop = D('Shop')->find($goods['shop_id']);
            $order['is_shop'] = (int) $shop['is_pei']; //是否由商家自己配送
            if ($order_id = D('Order')->add($order)) //推广ID 赋值了
            {
                $orderGoods['order_id'] = $order_id;
                D('Ordergoods')->add($orderGoods);

                $logs = array(
                    'type' => 'goods',
                    'user_id' => session('userInfo.user_id'),
                    'order_id' => $order_id,
                    'code' => '',
                    'need_pay' => $totalPrice,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'is_paid' => 0
                );
                $logs['log_id'] = D('Paymentlogs')->add($logs);
//                return outMessage(1, '下单成功，接下来选择支付方式！');
                echo json_encode(array('code' => 1, 'message' => '下单成功，接下来选择支付方式！', 'id' => $order_id));
            }
        }
    }

    private function pay()
    {
        if ($this->isPost())
        {
            $orderId = (int) $this->_post('id');
            $money = (float) $this->_post('money');
            $payType = $this->_post('payType');
            $payPwd = empty(htmlspecialchars($this->_post('password')))? '' : htmlspecialchars($this->_post('password')) ;
            if (empty($payType)) return outMessage(-1, '请选择支付方式');
            $order = D('Order')->find($orderId);
            if (empty($order) || $order['status'] != 1 || $order['user_id'] != session('userInfo.user_id')) return outMessage(-1, '该订单不存在');
            $logs = D('Paymentlogs')->where(array('order_id' => $order['order_id'], 'user_id'=>session('userInfo.user_id')))->find();
            if (empty($logs) || $logs['user_id'] != session('userInfo.user_id') || $logs['is_paid'] == 1) return outMessage(-1, '没有有效的支付记录');
            if ($payType == 'balance')  //  余额支付
            {
                if (!$payPwd) outMessage(-1, '请输入支付密码');
                $log['log_id'] = $logs['log_id'];
                $log['code'] = 'money';
                D('Paymentlogs')->save($log);
                $this->$payType($logs['log_id'], $payPwd);
            }
            elseif ($payType == 'asset')  //  资产支付
            {
                if (!$payPwd) outMessage(-1, '请输入支付密码');
                $log['log_id'] = $logs['log_id'];
                $log['code'] = 'asset';
                D('Paymentlogs')->save($log);
                $this->balance($logs['log_id'], $payPwd);
            }
            elseif ($payType == 'alipay')  //  支付宝支付
            {
//                return outMessage(-1, '支付宝支付');
                $log['log_id'] = $logs['log_id'];
                $log['code'] = 'alipay';
                D('Paymentlogs')->save($log);
                $url = $this->_pay($logs['log_id']);
//                header("location:{$url}");
                return outMessage(1, $url);
            }
            elseif ($payType == 'weixin')  //  微信支付
            {
//                return outMessage(-1, '微信支付');
                $log['log_id'] = $logs['log_id'];
                $log['code'] = 'weixin';
                D('Paymentlogs')->save($log);
                return $this->_pay($logs['log_id']);
//                $url = $this->_pay($logs['log_id']);
//                if ($url) return outMessage(1, $url);
//                return outMessage(-1, '请在用手机在微信客户端打开');
            }
            else
            {
                return outMessage(-1, '支付方式不存在');
            }
        }
    }

    /*protected function balance($logs_id, $password)
    {
        if (empty($logs_id)) return outMessage(-1, '没有有效的支付');
        if (!$detail = D('Paymentlogs')->find($logs_id)) return outMessage(-1, '没有有效的支付');
        if ($detail['code'] != 'money') return outMessage(-1, '没有有效的支付');

        $member = D('Users')->find(session('userInfo.user_id'));
        if ($member['pay_password'] != $password) return outMessage(-1, '支付密码错误');
        if ($member['money'] < $detail['need_pay']) return outMessage(-1, '很抱歉您的账户余额不足');
        $member['money'] -= $detail['need_pay'];
        if (D('Users')->save(array('user_id' => session('userInfo.user_id'), 'money' => $member['money'])))
        {
            $userMoney = array(
                'user_id' => session('userInfo.user_id'),
                'money' => $detail['need_pay'],
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip(),
                'intro' => '余额支付,' . $logs_id,
                'status' => 2,
                'type' => 2,
                'order_id' =>$detail['order_id'],
            );
            D('Usermoneylogs')->add($userMoney);
            D('Payment')->logsPaid($logs_id);
            return outMessage(1, '恭喜您支付成功啦！');
        }
//        if ($detail['type'] == 'ele') {
//            $this->ele_success('恭喜您支付成功啦！', $detail);
//        } elseif ($detail['type'] == 'ding') {
//            $this->ding_success('恭喜您支付成功啦！', $detail);
//        } elseif ($detail['type'] == 'goods') {
//            $this->goods_success('恭喜您支付成功啦！', $detail);
//        } elseif ($detail['type'] == 'gold' || $detail['type'] == 'money') {
//            $this->success('恭喜您充值成功', U('member/index/index'));
//            die();
//        } else {
//            $this->other_success('恭喜您支付成功啦！', $detail);
//        }
    }*/



    public function respond()
    {
//        session('ressss', $this->_post('res'));die();
//        $logId = (int) $this->_post('logId');
//        D('Payment')->logsPaid($logId);
        $code = $this->_get('code');
        if (empty($code)) return outMessage(-1, '没有该支付方式');
        $ret = D('Payment')->respond($code);
        if ($ret == false) return outMessage(-1, '支付验证失败！');
        if ($this->isPost())
        {
            echo 'SUCCESS';
            die;
        }
        return outMessage(1, '支付成功！');
//        $type = D('Payment')->getType();
//        $log_id = D('Payment')->getLogId();
//        $detail = D('Paymentlogs')->find($log_id);
//        if(!empty($detail)){
//            if ($detail['type'] == 'goods') {
//                if(empty($detail['order_id'])){
//                    $this->success('合并付款成功', U('mcenter/goods/index'));
//                }else{
//                    $this->goods_success('恭喜您支付成功啦！', $detail);
//                }
//
//            } elseif ($detail['type'] == 'gold' || $detail['type'] == 'money') {
//                $this->success('恭喜您充值成功', U('mcenter/index/index'));
//            }  else {
//                $this->other_success('恭喜您支付成功啦！', $detail);
//            }
//        }else{
//            $this->success('支付成功！', U('mcenter/index/index'));
//        }
    }
    public function yes($log_id)
    {
        $log_id  = (int)$log_id;
        $logs = D('Paymentlogs')->find($log_id);
        return outMessage(1, '支付成功！');
//        switch ($logs['type']){
//            case 'ele':
//                $this->ele_success('恭喜您支付成功啦！', $logs);
//                break;
//            case 'goods':
//                if(empty($logs['order_id'])){
//                    $this->success('合并付款成功', U('mcenter/goods/index'));
//                }else{
//                    $this->goods_success('恭喜您支付成功啦！', $logs);
//                }
//                break;
//            case 'ding':
//                $this->ding_success('恭喜您支付成功啦！', $logs);
//                break;
//            case 'tuan':
//                $this->other_success('恭喜您支付成功啦！', $logs);
//                break;
//            default:
//                $this->success('恭喜您充值成功', U('mcenter/index/index'));
//                break;
//        }
    }
    private function del()
    {
        if ($this->isPost())
        {
            $orderId = $this->_post('id');
            $order = D('Order')->find($orderId);
            if (empty($order) || $order['closed'] == 1) return outMessage(-1, '订单不存在或已删除');
            if (D('Order')->save(array('order_id' => $orderId, 'closed' => 1))) return outMessage(1, '删除成功');
            return outMessage(-1, '删除失败');
        }
    }

    private function lists()
    {
        if ($this->isPost())
        {
            $status = (int) $this->_post('status');
            $where['o.closed'] = 0;
            if ($status != 0) $where['o.status'] = $status;
            $where['o.user_id'] = session('userInfo.user_id');
//            switch ($status)
//            {
//                case 11: //  待付款
//                    $where['status'] = 1;
//                    break;
//                case 66: // 待使用
//                    $where['status'] = 6;
//                    break;
//                case 77: //  待评价
//                    $where['status'] = 7;
//                    break;
//                case 99: //  退款/售后
//                    $where['status'] = array('IN', '9,11');
//                    break;
//                default:
//                    break;
//            }
            $orderModel = D('Order o');
            $list = $orderModel
                ->field('o.order_id id,og.goods_id goodsId,g.title goodsName,o.shop_id shopId,s.shop_name shopName,o.status,g.photo picture,o.create_time orderTime,o.total_price payment,s.cate_id categoryId,o.coupon verify')
                ->join('uboss_order_goods og ON og.order_id = o.order_id')
                ->join('uboss_shop s ON s.shop_id = o.shop_id')
                ->join('uboss_goods g ON g.goods_id = og.goods_id')
                ->where($where)
                ->order('o.create_time DESC')
                ->page($this->page, $this->pageSize)
                ->select();
//            return outMessage($orderModel->getLastSql());
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }

    private function detail()
    {
        if ($this->isPost())
        {
            $orderId = $this->_post('id');
            if (!$orderId) return outMessage(-1, '订单id不可空');
            $order = D('Order o')
                ->field('o.order_id id,o.status,og.goods_id goodsId,g.title goodsName,s.tel,o.shop_id shopId,s.shop_name shopName,g.photo picture,
                g.`mall_price` originalPrice, o.total_price payment, o.orderno orderNumber,o.create_time orderTime,o.pay_time paymentTime,g.instructions label,
                o.success_time consumeTime,og.num,o.original_price total,o.bookinfo,o.coupon verify')
                ->join('uboss_order_goods og ON og.order_id = o.order_id')
                ->join('uboss_goods g ON g.goods_id = og.goods_id')
                ->join('uboss_shop s ON s.shop_id = g.shop_id')
                ->where(array('o.order_id' => $orderId))
                ->find();
            $order['label'] = explode('|', $order['label']);
            $bookInfo = json_decode($order['bookinfo'], true);
            unset($order['bookinfo']);
            $order['bookPeople'] = $bookInfo['bookPeople'];
            $order['bookTel'] = $bookInfo['bookTel'];
            return outJson($order);
        }
    }

    /**
     * 退款
     * @author Ginger
     * return
     */
    private function refund()
    {
        if ($this->isPost())
        {
            $orderId = (int) $this->_post('id');
            $content = htmlspecialchars($this->_post('content'));
            $pictures = htmlspecialchars($this->_post('pictures'));
            if (!$orderId || empty($content)) return outMessage(-1, '提交的信息不完整');
            $order = D('Order')->find($orderId);//1,2,3,5,9,11
            $goods = D('OrderGoods')->where(array('order_id' => $orderId))->find();
            if (empty($order) || $order['status'] == 1 || $order['status'] == 2 || $order['status'] == 3 || $order['status'] == 5 || $order['status'] == 9 || $order['status'] == 11) return outMessage(-1, '订单不能退款');
            if (D('Order')->save(array('order_id' => $orderId, 'status' => 9)))
            {
                $data = array(
                    'order_id' => $orderId,
                    'goods_id' => $goods['goods_id'],
                    'status' => 1,
                    'money' => $order['total_price'],
                    'reason' => $content,
                    'create_time' => NOW_TIME
                );
                D('BackGoods')->add($data);
                return outMessage(1, '提交成功');
            }
            return outMessage(-1, '失败');
        }
    }

    /**
     * 退款进度
     * @author Ginger
     * return
     */
    private function refundStatus()
    {
        $orderId = (int) $this->_post('id');
        if (!$orderId) return outMessage(-1, '订单id不能为空');
        $order = D('Order')->field('user_id,trade_style')->find($orderId);
        if ($order['user_id'] != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
        $orderBack = D('BackGoods')->field('back_id id,money,ok_time paymentTime,create_time')->where(array('order_id' => $orderId))->find();
        $orderBack['accountType'] = '原路返回（' . $this->refunAccount[$order['trade_style']] . '）';
        $orderBack['refundFlow'] = array(
            array(
                'label' => '申请已提交',
                'text' => '您的退款申请已提交请耐心等待工作人员审核',
                'time' => $orderBack['create_time']
            ),
            array(
                'label' => '审核中',
                'text' => '您的退款申请审核通过',
                'time' => $orderBack['paymentTime']
            ),
            array(
                'label' => '退款成功',
                'text' => '您的退款已返还至您的原支付账户',
                'time' => $orderBack['paymentTime']
            ),
            array(
                'label' => '退款已入账',
                'text' => '您的退款已返还至您的原支付账户',
                'time' => $orderBack['paymentTime']
            )
        );
        return outJson($orderBack);
    }

//    public function test()
//    {
//        D('Payment')->commission(100515);
//    }
}