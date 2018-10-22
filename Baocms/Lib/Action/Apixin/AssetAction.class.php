<?php

/**
 * File name: AssetAction.class.php
 * 资产
 * Created on: 2018/9/28 09:20
 * Created by: Ginger.
 */
class AssetAction extends BaseAction
{
    protected $type = array(1 => '充值', 2 => '变现', 3 => '购买会员', 4 => '申请创始人/股东', 5 => '支付');
    // 订单状态
    protected $status = array(
        1 => '待付款', 2 => '支付超时', 3 => '取消订单', 4 => '已付款', 5 => '付款失败', 6 => '待使用',
        7 => '待评价', 8 => '已完成', 9 => '退款中', 10 => '退款失败', 11 => '已退款', 12 => '分红开始结算'
    );
    //前端展示订单状态
    protected $showStatus = array(
        1 => '已失效', 2 => '已失效', 3 => '已失效', 4 => '已付款', 5 => '已失效', 6 => '已付款',
        7 => '已付款', 8 => '已完成', 9 => '已付款', 10 => '已付款', 11 => '已失效', 12 => '已结算'
    );
    protected $dateTime =array();

    public function asset()
    {
        if ($this->isPost())
        {
            $type = $this->_post('type');
            if ($type == 'getAsset')    //  资产信息
            {
                $this->info();
            }
            elseif ($type == 'orderDetail')    //  订单信息
            {
                $this->orderDetail();
            }
            elseif ($type == 'moreData')    //  更多数据
            {
                $this->moreData();
            }
            elseif ($type == 'recharge')    //  充值资产
            {
                $this->recharge();
            }
            elseif ($type == 'rechargeRecord')    //  资产充值记录
            {
                $this->rechargeRecord();
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

    /**
     * 资产充值记录
     * @author Ginger
     * @param $type 1 充值 2 变现 3 购买会员 4 申请创始人/股东 5 支付
     * return
     */
    protected function rechargeRecord($type = 1)
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            if (!$userId) return outMessage(-1, 'userId不能为空');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $assetModel = D('UserAssetLogs');
            $where['user_id'] = $userId;
            if ($type != 0) $where['type'] = $type;

            $list = $assetModel->field('asset_id id,pay_no flowNumber,create_time time,`money`/100 money,user_id userId,type')
                ->where($where)
                ->order('create_time DESC')
                ->page($this->page, $this->pageSize)
                ->select();
            if ($type == 0)
            {
                foreach ($list as $key => $val) $list[$key]['type'] = $this->type[$val['type']];
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }

    /**
     * 资产信息
     * @author Ginger
     * return
     */
    private function info()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致'.session('userInfo.user_id'));
            $moneyLogsModel = D('Usermoneylogs');
            $assets = $this->usersModel->field('asset assets')->find($userId);
//            $assetLogs = D('UserAssetLogs')->field('order_id')->where(array('type' => 2, 'user_id' => $userId))->order('create_time desc')->select();
//            $orderId = array();
//            foreach ($assetLogs as $v)
//            {
//                $orderId[] = $v['order_id'];
//            }
//            $where['order_id'] = array('IN', join(',', $orderId));
            $where['user_id'] = $paymentWhere['user_id'] = $userId;
            $where['status'] = array('EQ', 2);//结算
            $paymentWhere['status'] = 1;//预估
            $where['type'] = $paymentWhere['type'] = array(array('EQ', 5),array('EQ', 6), 'OR');//资产变现或者消费分成
            //本周开始、结束时间
            $beginWeek = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'));
            $endWeek = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
            $where['ok_time'] = array('BETWEEN', array($beginWeek, $endWeek));//本周
            $tSwkBalance = $moneyLogsModel->where($where)->sum('money');
            //上周开始、结束时间
            $beginLastWeek = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $endLastWeek = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
            $where['ok_time'] = array('BETWEEN', array($beginLastWeek, $endLastWeek));//上周
            $lastWeekBalance = $moneyLogsModel->where($where)->sum('money');
            //今日开始、结束时间
            $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $where['ok_time'] = $paymentWhere['create_time'] = array('BETWEEN', array($beginToday, $endToday));//今日结算 & 预估
            $todayBalance = $moneyLogsModel->where($where)->sum('money');
            $todayPayment = $moneyLogsModel->where($paymentWhere)->sum('money');
            //昨日开始、结束时间
            $beginYesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
            $endYesterday = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            $where['ok_time'] = $paymentWhere['create_time'] = array('BETWEEN', array($beginYesterday, $endYesterday));//昨日结算 & 预估
            $yesterdayBalance = $moneyLogsModel->where($where)->sum('money');
            $yesterdayPayment = $moneyLogsModel->where($paymentWhere)->sum('money');
            $data = array(
                'assets' => $assets['assets']/100,
                'tSwkBalance' => $tSwkBalance/100,
                'lastWeekBalance' => $lastWeekBalance/100,
                'today' => array('balance' => $todayBalance/100, 'payment' => $todayPayment/100),
                'yesterday' => array('balance' => $yesterdayBalance/100, 'payment' => $yesterdayPayment/100)
            );
            return outJson($data);
        }
    }

    /**
     * 订单明细
     * @author Ginger
     * return
     */
    private function orderDetail()
    {
        $userId = (int) $this->_post('userId');
        if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
        //$where['o.status'] = array('GT', 3);// 已付款
//        $assetLogs = D('UserAssetLogs')->field('order_id')->where(array('type' => 2, 'user_id' => $userId))->order('create_time desc')->select();
//        $shopId = array();
//        foreach ($assetLogs as $v)
//        {
//            $shopId[] = $v['shop_id'];
//        }
//        $where['o.order_id'] = array('IN', join(',', $shopId));
        $where['ua.type'] = 2;
        $where['ua.user_id'] = $userId;
        $orderModel = D('Order o');
        $list = $orderModel
            ->field('o.order_id id,g.title commodityName,g.photo picture,o.status,o.`total_price` paymentMoney,ua.`money` profit')
            ->join('uboss_user_asset_logs ua ON ua.order_id = o.order_id')
            ->join('uboss_order_goods og ON og.order_id = o.order_id')
            ->join('uboss_goods g ON g.goods_id = og.goods_id')
            ->where($where)
            ->order('ua.create_time desc')
            ->page($this->page, $this->pageSize)
            ->select();
        foreach ($list as $k => $v)
        {
            $list[$k]['paymentMoney'] = $v['paymentMoney']/100;
            $list[$k]['profit'] = $v['profit']/100;
            $list[$k]['orderState'] = $this->showStatus[$v['status']];
            unset($list[$k]['status']);
        }
        return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
    }

    /**
     * 折线图数据
     * @author Ginger
     * return
     */
    private function moreData()
    {
        if ($this->isPost())
        {
            $userId = (int)$this->_post('userId');
            $time = $this->_post('time');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $moneyLogsModel = D('Usermoneylogs');
//            $where['user_id'] = $userId;
            $where['status'] = array(array('eq', 2), array('eq', 1), 'OR');
            if ($time == 'today')
            {
                //今日开始、结束时间
                $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
                $where['ok_time|create_time'] = array('BETWEEN', array($beginToday, $endToday));
                $yesterdayData = $moneyLogsModel->field('money,status,create_time,ok_time')->where($where)->select();
                $data = array();
                foreach ($yesterdayData as $key => $item)
                {
                    if ($item['status'] == 1)
                    {
                        $data = $this->_day($data, $item, 'payment', $beginToday, $endToday);
                    }
                    elseif ($item['status'] == 2)
                    {
                        $data = $this->_day($data, $item, 'balance', $beginToday, $endToday);
                    }
                }
                return outJson($data);
            }
            elseif ($time == 'yesterday')
            {
                //昨日开始、结束时间
                $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
                $where['ok_time|create_time'] = array('BETWEEN', array($beginYesterday, $endYesterday));
                $yesterdayData = $moneyLogsModel->field('money,status,create_time,ok_time')->where($where)->select();
                $data = array();
                foreach ($yesterdayData as $key => $item)
                {
                    if ($item['status'] == 1)
                    {
                        $data = $this->_day($data, $item, 'payment', $beginYesterday, $endYesterday);
                    }
                    elseif ($item['status'] == 2)
                    {
                        $data = $this->_day($data, $item, 'balance', $beginYesterday, $endYesterday);
                    }
                }
                return outJson($data);
            }
            elseif ($time == 'week')
            {
                $beginLastSevenDays = mktime(0,0,0,date('m'),date('d')-6,date('y'));
                $endLastSevenDays = time();
                $where['ok_time|create_time'] = array('BETWEEN', array($beginLastSevenDays, $endLastSevenDays));
                $lastSevenData = $moneyLogsModel->field('money,status,create_time,ok_time')->where($where)->select();
                $data = array();
                foreach ($lastSevenData as $key => $item)
                {
                    if ($item['status'] == 1)
                    {
                        $data = $this->_sevenDays($data, $item, 'payment', $beginLastSevenDays, $endLastSevenDays);
                    }
                    elseif ($item['status'] == 2)
                    {
                        $data = $this->_sevenDays($data, $item, 'balance', $beginLastSevenDays, $endLastSevenDays);
                    }
                }
                return outJson($data);
            }
            elseif ($time == 'month')
            {
                //近30日开始、结束时间
                $beginLastThirtyDays = mktime(0,0,0,date('m'),date('d')-29,date('y'));
                $endLastThirtyDays = time();
                $where['ok_time|create_time'] = array('BETWEEN', array($beginLastThirtyDays, $endLastThirtyDays));
                $lastThirtyData = $moneyLogsModel->field('money,status,create_time,ok_time')->where($where)->select();
                $data = array();
                foreach ($lastThirtyData as $key => $item)
                {
                    if ($item['status'] == 1)
                    {
                        $data = $this->_thirtyDays($data, $item, 'payment', $beginLastThirtyDays, $endLastThirtyDays);
                    }
                    elseif ($item['status'] == 2)
                    {
                        $data = $this->_thirtyDays($data, $item, 'balance', $beginLastThirtyDays, $endLastThirtyDays);
                    }
                }
                return outJson($data);
            }
            else
            {
                return outMessage(-1,'非法请求，参数错误');
            }
        }
    }

    /**
     * 今日/昨日数据
     * @author Ginger
     * @param $data
     * @param $item
     * @param $cate
     * @param $beginDay
     * @param $endDay
     * return
     */
    private function _day($data, $item, $cate, $beginDay, $endDay)
    {
        $fourHour = 3600*4;
        $timeType = $cate == 'payment' ? 'create_time' : 'ok_time' ;
        if ($item[$timeType] < $beginDay + $fourHour)
        {
            $time = 0;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginDay + $fourHour) && ($item[$timeType] < $beginDay + (2 * $fourHour)))
        {
            $time = 4;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginDay + (2 * $fourHour)) && ($item[$timeType] < $beginDay + (3 * $fourHour)))
        {
            $time = 8;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginDay + (3 * $fourHour)) && ($item[$timeType] < $beginDay + (4 * $fourHour)))
        {
            $time = 12;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginDay + (4 * $fourHour)) && ($item[$timeType] < $beginDay + (5 * $fourHour)))
        {
            $time = 16;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginDay + (5 * $fourHour)) && ($item[$timeType] <= $endDay))
        {
            $time = 20;
            $data[$cate.$time]['value'] += $item['money'];
        }
        $data[$cate.$time]['time'] = $time;
        $data[$cate.$time]['Category'] = $cate;
        return $data;
    }
    /**
     * 最近7天数据
     * @author Ginger
     * @param $data
     * @param $item
     * @param $cate
     * @param $beginLastSevenDays
     * @param $endLastSevenDays
     * return
     */
    private function _sevenDays($data, $item, $cate, $beginLastSevenDays, $endLastSevenDays)
    {
        $oneDay = 3600*24;
        $timeType = $cate == 'payment' ? 'create_time' : 'ok_time' ;
        if ($item[$timeType] < $beginLastSevenDays + $oneDay)
        {
            $time = 1;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastSevenDays + $oneDay) && ($item[$timeType] < $beginLastSevenDays + (2 * $oneDay)))
        {
            $time = 2;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastSevenDays + (2 * $oneDay)) && ($item[$timeType] < $beginLastSevenDays + (3 * $oneDay)))
        {
            $time = 3;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastSevenDays + (3 * $oneDay)) && ($item[$timeType] < $beginLastSevenDays + (4 * $oneDay)))
        {
            $time = 4;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastSevenDays + (4 * $oneDay)) && ($item[$timeType] < $beginLastSevenDays + (5 * $oneDay)))
        {
            $time = 5;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastSevenDays + (5 * $oneDay)) && ($item[$timeType] < $beginLastSevenDays + (6 * $oneDay)))
        {
            $time = 6;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastSevenDays + (6 * $oneDay)) && ($item[$timeType] <= $endLastSevenDays))
        {
            $time = 7;
            $data[$cate.$time]['value'] += $item['money'];
        }
        $data[$cate.$time]['time'] = $time . '天';
        $data[$cate.$time]['Category'] = $cate;
        return $data;
    }
    /**
     * 最近30天的数据
     * @author Ginger
     * @param $data
     * @param $item
     * @param $cate
     * @param $beginLastThirtyDays
     * @param $endLastThirtyDays
     * return
     */
    private function _thirtyDays($data, $item, $cate, $beginLastThirtyDays, $endLastThirtyDays)
    {
        $fiveDays = 3600*24*5;
        $timeType = $cate == 'payment' ? 'create_time' : 'ok_time' ;
        if ($item[$timeType] < $beginLastThirtyDays + $fiveDays)
        {
            $time = 5;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastThirtyDays + $fiveDays) && ($item[$timeType] < $beginLastThirtyDays + (2 * $fiveDays)))
        {
            $time = 10;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastThirtyDays + (2 * $fiveDays)) && ($item[$timeType] < $beginLastThirtyDays + (3 * $fiveDays)))
        {
            $time = 15;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastThirtyDays + (3 * $fiveDays)) && ($item[$timeType] < $beginLastThirtyDays + (4 * $fiveDays)))
        {
            $time = 20;
            $data[$cate.$time]['value'] += $item['money'];
        }
        if (($item[$timeType] >= $beginLastThirtyDays + (4 * $fiveDays)) && ($item[$timeType] < $beginLastThirtyDays + (5 * $fiveDays)))
        {
            $time = 25;
            $data[$cate.$time]['value'] += $item['money'];
        }
//        if (($item[$timeType] >= $beginLastThirtyDays + (5 * $fiveDays)) && ($item[$timeType] < $beginLastThirtyDays + (6 * $fiveDays)))
        if (($item[$timeType] >= $beginLastThirtyDays + (5 * $fiveDays)) && ($item[$timeType] <= $endLastThirtyDays))
        {
            $time = 30;
            $data[$cate.$time]['value'] += $item['money'];
        }
        $data[$cate.$time]['time'] = $time . '天';
        $data[$cate.$time]['Category'] = $cate;
        return $data;
    }

    /**
     * 资产充值
     * @author Ginger
     * return
     */
    private function recharge()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $payType = $this->_post('payType');
            $money = (int) $this->_post('money');
            if ($payType != 'weixin' && $payType != 'alipay' && $payType != 'balance') return outMessage(-1, '支付方式不存在');
            $payType = $payType == 'balance'? 'money' : $payType ;
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $logs = array(
                'type' => 'assetRecharge',
                'user_id' => session('userInfo.user_id'),
                'code' => $payType,
                'need_pay' => $money*100,
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip(),
                'is_paid' => 0
            );

            if ($logs['log_id'] = D('Paymentlogs')->add($logs))
            {
                echo json_encode(array('code' => 1, 'message' => '请前往支付！（/api/recharge/pay）', 'id' => $logs['log_id']));
                return;
            }
            return outMessage(-1, '提交失败，请稍后重试');
        }
    }
}