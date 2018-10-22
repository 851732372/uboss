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
     * 完成订单
     * @author Ginger
     * return
     */
    public function completeOrder()
    {

    }

    /**
     * 结算订单分成
     * @author Ginger
     * return
     */
    public function settlementOrder()
    {
        
    }
}