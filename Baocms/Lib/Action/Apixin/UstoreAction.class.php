<?php

/**
 * File name: UstoreAction.class.php
 * 优店
 * Created on: 2018/9/23 16:29
 * Created by: Ginger.
 */
class UstoreAction extends BaseAction
{
    protected $storeType = array('1' => '旗舰店', '2' => '核心店', '3' => '人气店');
//    protected $applyPositionArray = array('总监' => 1, '经理' => 2, '主管' => 3);
    protected $applyPositionNum = array(1 => 5, 2 => 10, 3 => 20);
    public function ustore()
    {
        if ($this->isPost())
        {
            $type = $this->_post('type');
            if ($type == 'ustoreList')    //  优店列表
            {
                $this->ustoreList();
            }
            elseif ($type == 'ustoreDetail')    //  优店详情
            {
                $this->ustoreDetail();
            }
            elseif ($type == 'myUstore')    //  我的优店
            {
                $this->myUstore();
            }
            elseif ($type == 'myUstoreDetail')    //  我的优店详情
            {
                $this->myUstoreDetail();
            }
            elseif ($type == 'myUstoreOrder')    //  我的优店订单
            {
                $this->myUstoreOrder();
            }
            elseif ($type == 'myUstoreCommission')    //  我的优店分红数据
            {
                $this->myUstoreCommission();
            }
            elseif ($type == 'createShareholder')  //  新增股东
            {
                $this->createShareholder();
            }
            elseif ($type == 'createHounder')  //  新增创始人
            {
                $this->createHounder();
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

    private function ustoreList()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            if (!$userId) return outMessage(-1, 'userId不能为空');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $founderModel = D('Founder');
            $applyModel = D('Apply');
            $where['user_id'] = array('neq', $userId);
//            $apply = $applyModel->field('shop_id')->where($where)->select();
//            $where['shop_id'] = array('NOT IN', $this->getShopIds($apply));
            $where['founder_id'] = array('neq', 0);
            $list = D('Shop')->field('shop_id id,founder_id,logo,shop_name uShopName,status operate,photo picture,addr address')
                ->where($where)
                ->page($this->page, $this->pageSize)
                ->select();

            foreach ($list as $k => $v)
            {
                $list[$k]['type'] = $founderModel->getStoreType($v['founder_id']);
                unset($list[$k]['founder_id']);
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }

    protected function ustoreDetail()
    {
        if ($this->isPost())
        {
            $shopId = (int) $this->_post('shopId');
            if (!$shopId) return outMessage(-1, 'shopId不能为空');
            $where['shop_id'] = $shopId;
            $where['founder_id'] = array('neq', 0);;
            $shop = D('Shop')->field('shop_id,founder_id')->where($where)->field();
            if (empty($shop)) return outMessage(-1, '优店不存在');
            $applyModel = D('Apply');
            $applyInfo = D('Apply')->where(array('shop_id' => $shopId))->select();
            $data = array(
                1 => '总监' . $applyModel->getPositionNum($shopId, 1) . '/' . $this->applyPositionNum[1],
                2 => '经理' . $applyModel->getPositionNum($shopId, 2) . '/' . $this->applyPositionNum[2],
                3 => '主管' . $applyModel->getPositionNum($shopId, 3) . '/' . $this->applyPositionNum[3]
            );
            return outJson($data);
        }
    }

    /**
     * 我的优店
     * @author Ginger
     * return
     */
    private function myUstore()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            if (!$userId) return outMessage(-1, 'userId不能为空');

            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $founderModel = D('Founder');
            $applyModel = D('Apply');
            $shopModel = D('Shop');
            $where['user_id'] = array('eq', $userId);
            $apply = $applyModel->field('shop_id,apply_position')->where($where)->select();
            $where['shop_id'] = array('NOT IN', $this->getShopIds($apply));
            $where['founder_id'] = array('neq', 0);
            $list = $shopModel->field('shop_id id,founder_id,logo,shop_name uShopName,photo picture,addr address')
                ->where($where)
                ->page($this->page, $this->pageSize)
                ->select();

//            $data = $applyModel->find(array('where'=>array('user_id' => $userId, 'shop_id' => 44)));
////        return $this->_format($data);
//            echo $shopModel->getLastSql();return;
            $userInfo = D('Users')->getUserByUserId($userId);
            $dividemoneyModel = D('Dividemoney');
            foreach ($list as $k => $v)
            {
                $list[$k]['type'] = $this->storeType[$founderModel->getStoreType($v['founder_id'])];
                unset($list[$k]['founder_id']);
                $list[$k]['userName'] = $userInfo['nickname'];
                $list[$k]['level'] = $this->level[$userInfo['level_id']];
                $list[$k]['bonus'] = $dividemoneyModel->getMoney($v['id'])['total_money']/100;
                $list[$k]['stock'] = $applyModel->check($userId, $v['id'])['stock'];
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }

    /**
     * 我的优店详情
     * @author Ginger
     * return
     */
    private function myUstoreDetail()
    {
        if ($this->isPost())
        {
            $time = $this->_post('time');
            //今日
            $begin = $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end = $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            //本周
            $beginWeek = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'));
            $endWeek = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
            //上周
            $beginLastWeek = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $endLastWeek = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
            //本月
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
            if ($time == 'day')
            {
                $begin = $beginToday;
                $end = $endToday;
            }
            elseif ($time == 'week')
            {
                $begin = $beginWeek;
                $end = $endWeek;
            }
            elseif ($time == 'month')
            {
                $begin = $beginMonth;
                $end = $endMonth;
            }
            $data['tSwkBonus'] = $this->commission($beginWeek, $endWeek);
            $data['lastWeekBonus'] = $this->commission($beginLastWeek, $endLastWeek);
            $data['totalBonus'] = $this->commission();

            $shopId = (int) $this->_post('shopId');
            if (!$shopId) return outMessage(-1, '优店id不能为空');
            $data['shopFlow'] = D('Order')->where(array('shop_id' => $shopId, 'pay_time' => array('BETWEEN', array($begin, $end)), 'status' => array(array('eq', 4), array('gt', 5), 'OR')))->sum('total_price');
//            return outMessage(D('Order')->getLastSql());
            return outJson($data);
        }
    }

    /**
     * 根据起始时间查询U店分红数据
     * @author Ginger
     * @param int $begin
     * @param int $end
     * return
     */
    protected function commission($begin = 0, $end = 9999999999999999999999999)
    {
        return D('UserMoneyLogs')->where(array('create_time' => array('BETWEEN', array($begin, $end)), 'type' => 4, 'user_id' => session('userInfo.user_id')))->sum();
    }

    /**
     * 我的优店订单
     * @author Ginger
     * return
     */
    private function myUstoreOrder()
    {
        if ($this->isPost())
        {
            $shopId = (int) $this->_post('id');
            if (!$shopId) return outMessage(-1, 'id不能为空');
            $where['user_id'] = array('eq', session('userInfo.user_id'));
            $where['founder_id'] = array('neq', 0);
            $where['shop_id'] = $shopId;
            $shop = D('Shop')->field('shop_id')->where($where)->field();
            if (empty($shop)) return outMessage(-1, '优店不存在');

            $list = D('Order o')
                ->field('o.order_id id,u.face portrait,l.type type,o.total_price money,o.pay_time date,o.total_price money')
                ->join('Users u ON u.user_id = o.user_id')
                ->join('Usermoneylogs l ON l.order_id = o.order_id')
                ->where("o.shop_id = {$shopId} AND (l.type = 2 OR l.type = 22)")
                ->order('o.create_time DESC')
                ->page($this->page, $this->pageSize)
                ->select();
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }

    /**
     * 我的优店分红数据
     * @author Ginger
     * return
     */
    private function myUstoreCommission()
    {
        if ($this->isPost())
        {
            $shopId = (int) $this->_post('id');
            if (!$shopId) return outMessage(-1, 'id不能为空');

//            return outJson($data);
        }
    }

    /**
     * 申请股东
     * @author Ginger
     * return
     */
    private function createShareholder()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $shopId = (int) $this->_post('shopId');
            $applyPosition = (int) $this->_post('level');
            $payType = $this->_post('paymentType');
            $money = $this->_post('money');
            $stock = $this->_post('stock');

            if (!$userId || !$shopId) return outMessage(-1, '缺少参数');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            if ($applyPosition != 1 && $applyPosition != 2 && $applyPosition != 3) return outMessage(-1, '申请的职位不存在');
            if ($payType != 'weixin' && $payType != 'alipay' && $payType != 'balance') return outMessage(-1, '支付方式不存在');
            if ($money != 30000 && $money != 11000 && $money != 3600) return outMessage(-1, '支付金额错误');
            if ($stock != 10000 && $stock != 3000 && $stock != 1000) return outMessage(-1, '股份错误');
            $applyModel = D('Apply');
            $res = $applyModel->check($userId, $shopId);
            if (!empty($res)) return outMessage(-1, '您已经申请过该优店的股东了');
            $applyPositionNum = $applyModel->getPositionNum($shopId,$applyPosition);
            if ($applyPositionNum >= $this->applyPositionNum[$applyPosition]) return outMessage(-1, '抱歉！您申请的股东已满');
            $payType = $payType =='balance' ? 'money' : $payType ;
            $data = array(
                'user_id' => $userId,
                'apply_position' => $applyPosition,
                'shop_id' => $shopId,
                'stock' => $stock,
                'money' => $money*100,
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip()
            );
//            return outJson($data);
            if ($applyPosition == 1) $type = 'zongjian';
            if ($applyPosition == 2) $type = 'jingli';
            if ($applyPosition == 3) $type = 'zhuguan';
            $applyModel->startTrans();
            if ($applyModel->add($data))
            {
                $logs = array(
                    'type' => $type,
                    'user_id' => session('userInfo.user_id'),
                    'code' => $payType,
                    'need_pay' => $money*100,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'is_paid' => 0
                );

                if ($logs['log_id'] = D('Paymentlogs')->add($logs))
                {
                    $applyModel->commit();
                    echo json_encode(array('code' => 1, 'message' => '请前往支付！（/api/recharge/pay）', 'id' => $logs['log_id']));
                    return;
                }
            }
            $applyModel->rollback();
            return outMessage(-1, '提交失败，请稍后重试');
        }
    }

    /**
     * 申请创始人
     * @author Ginger
     * return
     */
    private function createHounder()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $cateId = (int) $this->_post('industry');
            $storeType = $this->_post('shopType');
            $payType = $this->_post('paymentType');
            $money = $this->_post('money');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            if ($payType != 'weixin' && $payType != 'alipay' && $payType != 'balance') return outMessage(-1, '支付方式不存在');
            if ($money != 120000) return outMessage(-1, '支付金额错误');
            $payType = $payType =='balance' ? 'money' : $payType ;
            if ($storeType == '旗舰店') $storeType = 1;
            if ($storeType == '核心店') $storeType = 2;
            if ($storeType == '人气店') $storeType = 3;

            $founderModel = D('Founder');
//            $res = $founderModel->check($userId, $storeType);
//            if (!empty($res)) return outMessage(-1, '您已经申请过了');
            $data = array(
                'user_id' => $userId,
                'store_type' => $storeType,
                'shop_cate_id' => $cateId,
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip()
            );
//            return outJson($data);
            $founderModel->startTrans();
            if ($founderModel->add($data))
            {
                $logs = array(
                    'type' => 'founder',
                    'user_id' => session('userInfo.user_id'),
                    'code' => $payType,
                    'need_pay' => $money*100,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'is_paid' => 0
                );

                if ($logs['log_id'] = D('Paymentlogs')->add($logs))
                {
                    $founderModel->commit();
                    echo json_encode(array('code' => 1, 'message' => '请前往支付！（/api/recharge/pay）', 'id' => $logs['log_id']));
                    return;
                }
            }
            $founderModel->rollback();
            return outMessage(-1, '提交失败，请稍后重试');
        }
    }


    

    /**
     * 拼接商家ID
     * @author Ginger
     * @param $array
     * return
     */
    protected function getShopIds($array)
    {
        $ids = array();
        foreach ($array as $v)
        {
            $ids[] = $v['shop_id'];
        }
        return join(',', $ids);
    }
}