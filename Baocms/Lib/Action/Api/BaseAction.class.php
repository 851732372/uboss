<?php

class BaseAction extends Action
{
    const FROND_DOMAIN = 'http://wx.uboss.net.cn';
    protected $page = 1;
    protected $pageSize = 10;
    protected $uid = 0;
    protected $openid = 'oKJ27038g7Sev-sDmpxsc6rjtQJw';
    protected $usersModel;
    protected $operate = array();
    protected $level = array(1 => '普通会员', 2 => '黄金会员', 3 => '钻石会员');
    protected $applyPosition = array(1 => '总监', 2 => '经理', 3 => '主管');

    // TODO: 定位信息
    protected $lat = 37.799225;
    protected $lng = 112.619287;
    protected function _initialize()
    {
        $param = $this->_post();
        $this->page = $param['current'] ? (int)$param['current'] : $this->page ;
        $this->pageSize = $param['pageSize'] ? (int)$param['pageSize'] : $this->pageSize ;
        $this->usersModel = D('Users');
//        session('openid', 'oKJ27038g7Sev-sDmpxsc6rjtQJw');
//        $userInfo = $this->usersModel
//            ->field('user_id,level_id')
//            ->where(array('user_id' => $this->uid))
//            ->find();
//        $this->uid = $userInfo['user_id'];
//        $this->level = $userInfo['level_id'];
//        $this->openid = $userInfo['openid'];
    }

//    protected function success($message, $jumpUrl = '', $time = 3000)
//    {
//        $str = '<script>';
//        $str .= 'parent.success("' . $message . '",' . $time . ',\'jumpUrl("' . $jumpUrl . '")\');';
//        $str .= '</script>';
//        exit($str);
//    }
//
//    protected function error($message, $time = 3000, $yzm = false)
//    {
//        $str = '<script>';
//        if ($yzm)
//        {
//            $str .= 'parent.error("' . $message . '",' . $time . ',"yzmCode()");';
//        }
//        else
//        {
//            $str .= 'parent.error("' . $message . '",' . $time . ');';
//        }
//        $str .= '</script>';
//        exit($str);
//    }
    /**
     * 是否用微信浏览器打开
     * @author Ginger
     * return
     */
    protected function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) return true;
        return false;
    }
    protected function checkFields($data = array(), $fields = array())
    {
        foreach ($data as $k => $val)
        {
            if (!in_array($k, $fields))
            {
                unset($data[$k]);
            }
        }
        return $data;
    }
    public function _empty()
    {
        return outMessage(-1, '来自不明IP：' . get_client_ip());
    }

    /**
     * 发送验证码
     * @author Ginger
     * @param $mobile
     * @param string $code
     * @param string $templateCode
     * return
     */
    protected function _sendSms($mobile, $code = '', $templateCode = '')
    {
        if (!isMobile($mobile) || strlen($mobile) != 11)
        {
            $data['msg'] = '手机号码格式错误';
            return $data;
        }
        else
        {
            $key = session('userInfo.user_id') . '-' . $mobile;
            session($key, $mobile);
//            $code = session('code');
//            if (empty($code))
//            {
                $code = rand_string(6, 1);
                session('code', $code);
//            }
            if (send($mobile, $code, $templateCode))
            {
                $data['status'] = 1;
                $data['msg'] = '发送成功';
                return $data;
            }
            else
            {
                $data['msg'] = '发送失败';
                return $data;
            }
        }
    }

    /**
     * 验证 验证码
     * @author Ginger
     * @param $mobile
     * @param $verifyCode
     * return
     */
    protected function _mobile($mobile, $verifyCode)
    {
        $key = session('userInfo.user_id') . '-' .  $mobile;
        $s_mobile = session($key);
        $s_code = session('code');
        $data = array();
        if ($mobile != $s_mobile)
        {
            $data['msg'] = $s_mobile.'手机号码和收取验证码的手机号不一致'.$mobile;
            return $data;
        }
        if ($verifyCode != $s_code)
        {
            $data['msg'] = '验证码不正确！';
            return $data;
        }
        $data['mobile'] = $mobile;
        session('code', null);
        return $data;
    }

    /**
     * 敏感词替换成*
     * @author Ginger
     * @param $content
     * return
     */
    protected function _checkWords($content)
    {
        $sensitiveModel = D('Sensitive');
        if ($words = $sensitiveModel->checkWords($content))
        {
            return $this->_checkWords(str_replace($words, '*', $content));
        }
        else
        {
            return $content;
        }
    }

    protected function _pay($logs_id)
    {
        $log_id = (int) $logs_id;
        $logs = D('Paymentlogs')->find($log_id);
        return D('Payment')->getCode($logs);
    }

    protected function balance($logs_id, $password)
    {
        if (empty($logs_id)) return outMessage(-1, '没有有效的支付');
        if (!$detail = D('Paymentlogs')->find($logs_id)) return outMessage(-1, '没有有效的支付');
        if ($detail['code'] != 'money' && $detail['code'] != 'asset') return outMessage(-1, '没有有效的支付');

        $member = D('Users')->find(session('userInfo.user_id'));
        if ($member['pay_password'] != $password) return outMessage(-1, '支付密码错误');

        if ($detail['code'] == 'money') //  余额支付
        {
            if ($member['money'] < $detail['need_pay']) return outMessage(-1, '很抱歉您的账户余额不足');
            $member['money'] -= $detail['need_pay'];
            if (D('Users')->save(array('user_id' => session('userInfo.user_id'), 'money' => $member['money'])))
            {
                $userMoney = array(
                    'user_id' => session('userInfo.user_id'),
                    'money' => $detail['need_pay'],
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'intro' => $detail['type'] == 'scan' ? '扫码支付,' . $logs_id : '余额支付,' . $logs_id,
                    'status' => 2,
                    'type' => $detail['type'] == 'scan' ? 22 : 2,
                    'order_id' =>$detail['order_id'],
                );
                D('Usermoneylogs')->add($userMoney);
                D('Payment')->logsPaid($logs_id);
                return outMessage(1, '恭喜您支付成功啦！');
            }
        }
        elseif ($detail['code'] == 'asset') //  资产支付
        {
            if ($member['asset'] < $detail['need_pay']) return outMessage(-1, '很抱歉您的资产余额不足');
            $member['asset'] -= $detail['need_pay'];
            if (D('Users')->save(array('user_id' => session('userInfo.user_id'), 'asset' => $member['asset'])))
            {
                $userAsset = array(
                    'user_id' => session('userInfo.user_id'),
                    'money' => $detail['need_pay'],
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'intro' => '资产支付,' . $logs_id,
                    'status' => 1,
                    'type' => 5,
                    'order_id' =>$detail['order_id'],
                );
                D('UserAssetLogs')->add($userAsset);
                D('Payment')->logsPaid($logs_id);
                return outMessage(1, '恭喜您支付成功啦！');
            }
        }
        else
        {
            return outMessage(-1, '没有有效的支付');
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
    }

    /**
     * 格式化数据
     * @author Ginger
     * @param $info
     * return
     */
    protected function _option($info)
    {
        $data = array();
        foreach ($info as $val)
        {
            $value = $val;
            $label = array_shift($value);
            $items = array();
            foreach ($value as $item)
            {
                $name = explode('|', $item);
                $items[] = array(
                    'name' => $name[0],
                    'price' => $name[1]
                );
            }
            $data[] = array(
                'label' => $label,
                'value' => $items
            );
            unset($items);
        }
        return $data;
    }

    /**
     * 人均消费
     * @author Ginger
     * @param $shopId
     * return
     */
    public function price($shopId)
    {
        $where['shop_id'] = $shopId;
        $goodsModel = D('Goods');
        $count = $goodsModel->where($where)->count('goods_id');
        $goods = $goodsModel->where($where)->sum('mall_price');
        $price = floor($goods/$count);
        if (D('Shop')->where($where)->setField('price', $price))
        {
//            return outMessage(1, '成功');
        }
//        return outMessage(-1, '失败');
    }

    public function perPrice($shopId)
    {
        $where['shop_id'] = $shopId;
        $where['closed'] = 0;
        $where['audit'] = 1;
        $goodsModel = D('Goods');
        $price = $goodsModel->where($where)->order('mall_price ASC')->find();
        if (D('Shop')->where(array('shop_id' => $shopId))->setField('price', $price['mall_price']))
        {
//            return outMessage(1, '成功');
        }
//        return outMessage(-1, '失败');
    }
    
}
