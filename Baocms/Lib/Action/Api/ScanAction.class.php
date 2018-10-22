<?php

/**
 * File name: ScanAction.class.php
 * 扫一扫付款
 * Created on: 2018/10/16 16:34
 * Created by: Ginger.
 */
class ScanAction extends BaseAction
{
    /**
     * 扫码付款
     * @author Ginger
     * return
     */
    public function scan()
    {
        $code = trim($this->_post('code'));
        $price = (int)$this->_post('price');//支付价
        $originalPrice = (int)$this->_post('originalPrice');//原价
        if (!$this->isPost()) return outMessage(-1, '参数错误');
        if (!empty($code))
        {
            $shopModel = D('Shop');
            $code = authCode($code,'DECODE');
            $shop = $shopModel->field('shop_id shopId,proportions,shop_name shopName')->where(array('code' => $code))->find();
            if (empty($shop)) return outMessage(-1, '参数错误');
            return outJson($shop);
        }
        elseif (!empty($price) && !empty($originalPrice))
        {
            $userId = (int) $this->_post('userId');
            $shopId = (int) $this->_post('shopId');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $shopProportions = D('Shop')->get_proportions($shopId);
            $totalPrice = mPrice($originalPrice, $shopProportions, session('userInfo.level_id'));
            $totalPrice = (int)$totalPrice;
            $different = $price - $totalPrice;
            if (abs($different) > 2) return outMessage(-1, '支付金额错误');
            $this->createOrder($totalPrice, $originalPrice, $shopId, $shopProportions);
        }
        else
        {
            return outMessage(-1, '缺少参数');
        }

    }

    /**
     * 创建支付订单
     * @author Ginger
     * @param $price 支付价
     * @param $originalPrice 原价
     * @param $shopId 商家id
     * @param $shopProportions 商家提点
     * return
     */
    protected function createOrder($price, $originalPrice, $shopId, $shopProportions)
    {
        if ($this->isPost())
        {
            $ip = get_client_ip();
            //总订单
            $order = array(
                'user_id' => session('userInfo.user_id'),
                'shop_id' => $shopId,
                'create_time' => NOW_TIME,
                'create_ip' => $ip,
                'total_price' => $price,
                'original_price' => $originalPrice,
                'precent' => $shopProportions,
                'orderno' => createNo(),
                'addr_id' => 0,
                'status' => 1,
                'type' => 2
            );

            $shop = D('Shop')->find($shopId);
            $order['is_shop'] = (int) $shop['is_pei']; //是否由商家自己配送
            if ($order_id = D('Order')->add($order)) //推广ID 赋值了
            {
                $logs = array(
                    'type' => 'scan',
                    'user_id' => session('userInfo.user_id'),
                    'order_id' => $order_id,
                    'code' => '',
                    'need_pay' => $price,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'is_paid' => 0
                );
                $logs['log_id'] = D('Paymentlogs')->add($logs);
                echo json_encode(array('code' => 1, 'message' => '下单成功，接下来选择支付方式！', 'id' => $order_id));
            }
            else
            {
                return outMessage(-1, '失败');
            }
        }
    }
    public function jie()
    {
        $str = trim($this->_post('code'));
        echo '解密：'.authCode($str,'DECODE');
    }
}