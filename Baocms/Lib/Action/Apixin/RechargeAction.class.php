<?php

/**
 * File name: PaymentAction.class.php
 * 充值
 * Created on: 2018/9/28 11:19
 * Created by: Ginger.
 */
class RechargeAction extends BaseAction
{
    public function pay()
    {
        if ($this->isPost())
        {
            $id = (int) $this->_post('id');
            if (!$id) return outMessage(-1, 'id不能为空');
            $logs = D('Paymentlogs')->where(array('log_id' => $id, 'user_id' => session('userInfo.user_id')))->find();
            if (empty($logs) || $logs['user_id'] != session('userInfo.user_id') || $logs['is_paid'] == 1) return outMessage(-1, '没有有效的支付记录');
            if ($logs['code'] == 'money')
            {
                $payPwd = empty(htmlspecialchars($this->_post('password'))) ? false : htmlspecialchars($this->_post('password')) ;
                if (!$payPwd) return outMessage(-1, '请输入支付密码');
                $this->balance($id, $payPwd);
            }
            elseif ($logs['code'] == 'alipay')
            {
                return outMessage(1, $this->_pay($logs['log_id']));
            }
            elseif ($logs['code'] == 'weixin')
            {
                return $this->_pay($logs['log_id']);
            }
            else
            {
                return outMessage(-1,'支付方式不存在');
            }

//            if ($type == 'money')    //  余额充值
//            {
//                $this->money();
//            }
//            elseif ($type == 'asset')    //  充值资产(创始人、股东)
//            {
//                $this->asset();
//            }
//            else
//            {
//                return outMessage(-1,'非法请求，参数错误');
//            }
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }
}