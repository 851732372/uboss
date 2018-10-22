<?php

/**
 * File name: TestAction.class.php
 * 测试
 * Created on: 2018/9/29 18:40
 * Created by: Ginger.
 */
class TestAction extends BaseAction
{
    public function index()
    {
        $log_id = $this->_get('log_id');
        if ((int) $log_id) header('location:http://m.uboss.net.cn/api/order/order/log_id/' . $log_id . '.html');
        $form = <<<STR
<form action="/api/order/order.html" method='post'>
    <input type='text' name='id' value='100613'>
    <input type='text' name='type' value='payOrder'>
    <input type='text' name='payType' value='weixin'>
    <input type='submit' value='提交'>
</form>
STR;
        echo $form;
    }

    public function pay()
    {
        $log_id = (int) $_REQUEST['log_id'];

        $logs = D('Paymentlogs')->find($log_id);
        return D('Payment')->getCode($logs);
    }

    public function test()
    {
        $_SESSION['openid'] = 'oKJ27038g7Sev-sDmpxsc6rjtQJw';
//        $res = session('openid');
//        var_dump($res);
        $where['openid'] = 'oKJ27038g7Sev-sDmpxsc6rjtQJw';
        $info = $this->usersModel->where($where)->find();
        session('userInfo', $info);
        $data = array(
            'id' => $info['user_id'],
            'user' => $info['nickname'],
            'portrait' => $info['face'],
            'tel' => $info['mobile'],
            'authentication' => $info['is_reg'],
            'member' => $info['level_id'],
            'email' => $info['email'],
            'payPwd' => $info['pay_password'] ? true : false
        );
        return outJson($data);
    }

    public function location()
    {
        $local = D('Near')-> GetLocation();
        var_dump($local);
    }

    public function dateTime()
    {
            $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $beginYesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
            $endYesterday = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            $beginLastWeek = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $endLastWeek = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
            $beginWeek = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'));
            $endWeek = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
            $beginLastSevenDays = mktime(0,0,0,date('m'),date('d')-6,date('y'));
            $endLastSevenDays = time();
            $beginLastThirtyDays = mktime(0,0,0,date('m'),date('d')-29,date('y'));
            $endLastThirtyDays = time();

            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
            $arr = array(
                '今日' => date('Y-m-d H:i:s', $beginToday) . '-' . date('Y-m-d H:i:s', $endToday),
                '昨日' => date('Y-m-d H:i:s', $beginYesterday) . '-' . date('Y-m-d H:i:s', $endYesterday),
                '上周' => date('Y-m-d H:i:s', $beginLastWeek) . '-' . date('Y-m-d H:i:s', $endLastWeek),
                '本周' => date('Y-m-d H:i:s', $beginWeek) . '-' . date('Y-m-d H:i:s', $endWeek),
                '近7日' => date('Y-m-d H:i:s', $beginLastSevenDays) . '-' . date('Y-m-d H:i:s', $endLastSevenDays),
                '近30日' => date('Y-m-d H:i:s', $beginLastThirtyDays) . '-' . date('Y-m-d H:i:s', $endLastThirtyDays),
                '本月' => date('Y-m-d H:i:s', $beginMonth) . '-' . date('Y-m-d H:i:s', $endMonth),
            );
            return outJson($arr);
    }


    public function totalPrice()
    {
        return outMessage($this->mPrice($this->_param('price'), $this->_param('profit'), $this->_param('level_id')));
    }

    protected function mPrice($price, $shopProfit, $level = 1)
    {
        $proportions = array(
            1 => 0,
            2 => 0.45,
            3 => 0.80
        );
        return $price - ($price * $shopProfit * $proportions[$level]) / 100;
    }

    public function price()
    {
        $where['shop_id'] = (int) $this->_post('shopId');
        $goodsModel = D('Goods');
        $count = $goodsModel->where($where)->count('goods_id');
        $goods = $goodsModel->where($where)->sum('mall_price');
        $price = floor($goods/$count);
        if (D('Shop')->where($where)->setField('price', $price))
        {
            return outMessage(1, '成功');
        }
        return outMessage(-1, '失败');
    }
}