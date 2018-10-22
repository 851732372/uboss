<?php

/**
 * File name: CrontabAction.class.php
 * 定时任务
 * Created on: 2018/10/11 16:51
 * Created by: Ginger.
 */
class CrontabAction extends Action
{

    /**
     * 取消订单
     * @author Ginger
     * return
     */
    public function cancelOrder()
    {
        $cancelTime = 60*60*24;
        $where['create_time'] = array('lt', NOW_TIME - $cancelTime);
        $where['status'] = 1;
        $orderModel = D('Order');
        $order = $orderModel->field('order_id')->where($where)->select();
        if (empty($order)) return outMessage(-1, '无信息');
        $orderIdArray = array();
        foreach ($order as $val)
        {
            $orderIdArray[] = $val['order_id'];
        }
        $orderIds = join(',', $orderIdArray);
        if ($orderModel->where(array('order_id' => array('IN', $orderIds)))->setField('status', 2))
        {
            return outMessage(1, '执行成功');
        }
        return outMessage(-1, '执行失败');
    }

    /**
     * 订单完成
     * @author Ginger
     * return
     */
    public function completeOrder()
    {
        $completeTime = 60*60*24*3;
        $where['use_time'] = array('lt', NOW_TIME - $completeTime);
        $where['status'] = 7;
        $orderModel = D('Order');
        $order = $orderModel->field('order_id')->where($where)->select();
        if (empty($order)) return outMessage(-1, '无信息');
        $orderIdArray = array();
        foreach ($order as $val)
        {
            $orderIdArray[] = $val['order_id'];
        }
        $orderIds = join(',', $orderIdArray);
        if ($orderModel->where(array('order_id' => array('IN', $orderIds)))->setField(array('status' => 8, 'success_time' => NOW_TIME)))
        {
            return outMessage(1, '执行成功');
        }
        return outMessage(-1, '执行失败');
    }

    /**
     * 订单结算
     * @author Ginger
     * return
     */
    public function settlementOrder()
    {
        $completeTime = 60*60*24*3;
        $where['success_time'] = array('lt', NOW_TIME - $completeTime);
        $where['status'] = 8;
        $orderModel = D('Order');
        $order = $orderModel->field('order_id')->where($where)->select();
        if (empty($order)) return outMessage(-1, '无信息');
        $orderIdArray = array();
        foreach ($order as $val)
        {
            $this->_settlementAsset($val['order_id']);
            $this->_settlementMoney($val['order_id']);
            $this->shopSettlement($val['order_id']);
            $orderIdArray[] = $val['order_id'];
        }
        $orderIds = join(',', $orderIdArray);
        if ($orderModel->where(array('order_id' => array('IN', $orderIds)))->setField(array('status' => 12)))
        {
            return outMessage(1, '执行成功');
        }
        return outMessage(-1, '执行失败');
    }

    /**
     * 结算消费分成
     * @author Ginger
     * @param $orderId
     * return
     */
    private function _settlementMoney($orderId)
    {
        $usersModel = D('Users');
        $moneyModel = D('Usermoneylogs');
        $where['order_id'] = $orderId;
        $where['type'] = 5;
        $where['status'] = 1;
        $userMoney = $moneyModel->where($where)->select();
        if (empty($userMoney)) return outMessage(-1, '无信息');
        foreach ($userMoney as $item)
        {
            $usersModel->where(array('user_id' => $item['user_id']))->setInc('money', $item['money']);
            $moneyModel->where(array('log_id' => $item['log_id']))->setField(array('status' => 2, 'ok_time' => NOW_TIME));
        }
    }

    /**
     * 结算资产变现
     * @author Ginger
     * @param $orderId
     * return
     */
    private function _settlementAsset($orderId)
    {
        $usersModel = D('Users');
        $moneyModel = D('Usermoneylogs');
        $assetModel = D('UserAssetLogs');
        $where['order_id'] = $orderId;
        $where['type'] = 6;
        $where['status'] = 1;
        $userMoney = $moneyModel->where($where)->select();
        if (empty($userMoney)) return outMessage(-1, '无信息');
        foreach ($userMoney as $item)
        {
            $usersModel->where(array('user_id' => $item['user_id']))->setInc('money', $item['money']);
            $moneyModel->where(array('log_id' => $item['log_id']))->setField(array('status' => 2, 'ok_time' => NOW_TIME));
            $usersModel->where(array('user_id' => $item['user_id']))->setDec('asset', $item['money']);
        }
        $assetModel->where(array('order_id' => $orderId, 'type' => 2))->setField('status', 1);
    }

    /**
     * 商家结算
     * @author Ginger
     * @param $orderId
     * return
     */
    private function shopSettlement($orderId)
    {
        $shopModel = D('Shop');
        $shopMoneyModel = D('ShopMoneyLogs');
        $where['audit'] = 0;
        $where['order_id'] = $orderId;
        $where['type'] = 2;
        $shopMoney = $shopMoneyModel->where($where)->find();
        if (empty($shopMoney)) return outMessage(-1, '无信息');
        $shopModel->where(array('shop_id' => $shopMoney['shop_id']))->setInc('money', $shopMoney['money']);
        $shopMoneyModel->where(array('shop_log_id' => $shopMoney['shop_log_id']))->setField(array('audit' => 1, 'ok_time' => NOW_TIME));
    }

    /**
     * 店铺评分
     * @author Ginger
     * @param $shopId
     * return
     */
    public function shopScore()
    {
        $commentModel = D('Comment');
        $shopIds = $commentModel->field('shop_id')->where('status = 2')->group('shop_id')->select();
        foreach ($shopIds as $item)
        {
            $commentModel->shopScore($item['shop_id']);
        }
    }
}