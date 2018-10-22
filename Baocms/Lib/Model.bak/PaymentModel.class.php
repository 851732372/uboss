<?php

/*
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.taobao.com
 * 邮件: youge@baocms.com  QQ 800026911
 */

class PaymentModel extends CommonModel {

	protected $pk = 'payment_id';
	protected $tableName = 'payment';
	protected $token = 'payment';
	protected $types = array('goods' => '商城购物', 'gold' => '金块充值', 'tuan' => '生活购物', 'money' => '余额充值', 'ele' => '在线订餐', 'ding' => '在线订座', 'assetRecharge' => '资产充值', 'moneyRecharge' => '余额充值', 'buyMember' => '购买会员' );
	protected $type = null;
	protected $log_id = null;

	public function getType() {
		return $this -> type;
	}

	public function getLogId() {
		return $this -> log_id;
	}

	public function getTypes() {
		return $this -> types;
	}

	public function getPayments($mobile = false) {
		$datas = $this -> fetchAll();
		$return = array();
		foreach ($datas as $val) {
			if ($val['is_open']) {
				if ($mobile == false) {
					if (!$val['is_mobile_only'])
						$return[$val['code']] = $val;
				} else {
					if ($val['code'] != 'tenpay') {
						$return[$val['code']] = $val;
					}
				}
			}
		}
		if (!is_weixin()) {
			unset($return['weixin']);
		}

		if (is_weixin()) {
			unset($return['alipay']);
		}

		return $return;
	}

	public function _format($data) {
		$data['setting'] = unserialize($data['setting']);
		return $data;
	}

	public function respond($code) {
		$payment = $this -> checkPayment($code);
		if (empty($payment)) return false;
//		if (defined('IN_MOBILE')) {
			require_cache(APP_PATH . 'Lib/Payment/' . $code . '.mobile.class.php');
//		} else {
//			require_cache(APP_PATH . 'Lib/Payment/' . $code . '.class.php');
//		}
		$obj = new $code();
		return $obj -> respond();
	}

	public function getCode($logs) {
		$CONFIG = D('Setting') -> fetchAll();
		$datas = array('subject' => $CONFIG['site']['sitename'] . $this -> types[$logs['type']], 'logs_id' => $logs['log_id'], 'logs_amount' => $logs['need_pay'] / 100, );
		$payment = $this -> getPayment($logs['code']);
//		if (defined('IN_MOBILE')) {
			require_cache(APP_PATH . 'Lib/Payment/' . $logs['code'] . '.mobile.class.php');
//		} else {
//			require_cache(APP_PATH . 'Lib/Payment/' . $logs['code'] . '.class.php');
//		}

		$obj = new $logs['code']();
//		if (empty($obj)) return false;
		return $obj -> getCode($datas, $payment);
	}

	public function checkMoney($logs_id, $money) {
		$money = (int)($money);
		$logs = D('Paymentlogs') -> find($logs_id);
		if ($logs['need_pay'] == $money)
			return true;
		return false;
	}

	public function logsPaid($logs_id) {
		$this -> log_id = $logs_id;
		//用于外层回调
		$logs = D('Paymentlogs') -> find($logs_id);
		$usersModel = D('Users');
		if (!empty($logs) && !$logs['is_paid']) {
			$data = array('log_id' => $logs_id, 'is_paid' => 1, );
			if ( D('Paymentlogs') -> save($data)) {//总之 先更新 然后再处理逻辑  这里保障并发是安全的
				$ip = get_client_ip();
				D('Paymentlogs') -> save(array('log_id' => $logs_id, 'pay_time' => NOW_TIME, 'pay_ip' => $ip));
				$this -> type = $logs['type'];

				if ($logs['type'] == 'gold') {
					D('Users') -> updateCount($logs['user_id'], 'gold', (int)($logs['need_pay'] / 100));
					D('Usergoldlogs') -> add(array('user_id' => $logs['user_id'], 'gold' => (int)($logs['need_pay'] / 100), 'create_time' => NOW_TIME, 'create_ip' => $ip, 'intro' => '充值金块，支付记录ID：' . $logs['log_id'], ));
					//====================微信余额变动通知===========================

					$limit = round($logs['need_pay'] / 100, 2);
					$users = D('Users') -> find($logs['user_id']);
					$balance = round($users['money'] / 100, 2);
					include_once "Baocms/Lib/Net/Wxmesg.class.php";
					$_data_balance = array('url' => 'http://' . $_SERVER['HTTP_HOST'] . '/mcenter/index/index', 'topcolor' => '#F55555', 'first' => '亲,您的生活宝账户有变动', 'remark' => '有疑问请联系生活宝：' . $this -> CONFIG['site']['tel'], 'accountType' => '生活宝会员账户', 'operateType' => '费用支出', 'operateInfo' => '充值金块', 'limit' => '-' . $limit . '元', 'balance' => $balance . '元');
					$balance_data = Wxmesg::pay($_data_balance);
					$return = Wxmesg::net($logs['user_id'], 'OPENTM201495900', $balance_data);

					//====================微信余额变动通知==============================
					return true;
				} elseif ($logs['type'] == 'money') {
					D('Users') -> updateCount($logs['user_id'], 'money', $logs['need_pay']);
					D('Usermoneylogs') -> add(array('user_id' => $logs['user_id'], 'money' => $logs['need_pay'], 'create_time' => NOW_TIME, 'create_ip' => $ip, 'intro' => '余额充值，支付记录ID：' . $logs['log_id'], ));
					//====================微信余额变动通知===========================
					$limit = round($logs['need_pay'] / 100, 2);
					$users = D('Users') -> find($logs['user_id']);
					$balance = round($users['money'] / 100, 2);
					include_once "Baocms/Lib/Net/Wxmesg.class.php";
					$_data_balance = array('url' => 'http://' . $_SERVER['HTTP_HOST'] . '/mcenter/index/index', 'topcolor' => '#F55555', 'first' => '亲,您的生活宝账户有变动', 'remark' => '有疑问请联系生活宝：' . $this -> CONFIG['site']['tel'], 'accountType' => '生活宝会员账户', 'operateType' => '费用收入', 'operateInfo' => '充值余额', 'limit' => '+' . $limit . '元', 'balance' => $balance . '元');
					$balance_data = Wxmesg::pay($_data_balance);
					$return = Wxmesg::net($logs['user_id'], 'OPENTM201495900', $balance_data);

					//====================微信余额变动通知==============================
					return true;
				} elseif ($logs['type'] == 'tuan') {//抢购都是发送抢购券！
					$member = D('Users') -> find($logs['user_id']);
					$codes = array();
					//抢购券
					$obj = D('Tuancode');
					$order = D('Tuanorder') -> find($logs['order_id']);
					$tuan = D('Tuan') -> find($order['tuan_id']);
					//这里逻辑要修改
					for ($i = 0; $i < $order['num']; $i++) {
						$local = $obj -> getCode();
						$insert = array('user_id' => $logs['user_id'], 'shop_id' => $tuan['shop_id'], 'order_id' => $order['order_id'], 'tuan_id' => $order['tuan_id'], 'code' => $local, 'price' => $tuan['price'], 'real_money' => (int)($order['need_pay'] / $order['num']), //退款的时候用
						'real_integral' => (int)($order['use_integral'] / $order['num']), //退款的时候用
						'fail_date' => $tuan['fail_date'], 'settlement_price' => $tuan['settlement_price'], 'create_time' => NOW_TIME, 'create_ip' => $ip, );
						$codes[] = $local;
						$obj -> add($insert);
					}
					D('Tuanorder') -> save(array('order_id' => $order['order_id'], 'status' => 1));
					//设置已付款
					D('Tuan') -> updateCount($tuan['tuan_id'], 'sold_num');
					//更新卖出产品
					D('Tuan') -> updateCount($tuan['tuan_id'], 'num', -$order['num']);

					D('Sms') -> sendSms('sms_tuan', $member['mobile'], array('code' => join(',', $codes), 'nickname' => $member['nickname'], 'tuan' => $tuan['title'], ));
					D('Users') -> prestige($member['user_id'], 'tuan');
					$tg = D('Users') -> checkInvite($order['user_id'], $tuan['price']);
					if ($tg !== false) {

						D('Users') -> addIntegral($tg['uid'], $tg['integral'], "分享获得积分！");
					}
					D('Tongji') -> log(1, $logs['need_pay']);
					//统计
					//发送短信
					D('Sms') -> tuanTZshop($tuan['shop_id']);

					return true;
				} elseif ($logs['type'] == 'ele') {//餐饮订餐
					D('Eleorder') -> save(array('order_id' => $logs['order_id'], 'status' => 1, 'is_pay' => 1));
					$order = D('EleOrder') -> where('order_id =' . $logs['order_id']) -> find();
					$member = D('Users') -> find($logs['user_id']);
					$shops = D('Shop') -> find($order['shop_id']);
					$ua = D('UserAddr');
					$dv = D('DeliveryOrder');
					$uaddr = $ua -> where('user_id =' . $order['user_id']) -> find();

					//在线支付成功以后，进入配送员判断
					if ($shops['is_pei'] == 0) {
						$dv_data = array('type' => 1, 'type_order_id' => $order['order_id'], 'delivery_id' => 0, 'shop_id' => $order['shop_id'], 'user_id' => $order['user_id'], 'shop_name' => $shops['shop_name'], 'shop_addr' => $shops['addr'], 'shop_mobile' => $shops['tel'], 'user_name' => $member['nickname'], 'user_addr' => $uaddr['addr'], 'user_mobile' => $member['mobile'], 'create_time' => time(), 'update_time' => 0, 'status' => 1);

						$dv -> add($dv_data);
					}
					D('Sms') -> sendSms('sms_ele', $member['mobile'], array('nickname' => $member['nickname'], 'shopname' => $shops['shop_name'], ));
					D('Tongji') -> log(3, $logs['need_pay']);
					//统计
					//通知商家
					D('Sms') -> eleTZshop($logs['order_id']);
				} elseif ($logs['type'] == 'ding') {//订座定金
                    $order_no = D('Shopdingorder') -> where('order_id = ' . $logs['order_id']) -> find();
                    D('Shopdingorder') -> save(array('order_id' => $logs['order_id'], 'status' => 1));
                    D('Shopdingyuyue') -> where('order_no = ' . $order_no['order_no']) -> save(array('is_pay' => 1));

                    $member = D('Users') -> find($logs['user_id']);
                    $shops = D('Shop') -> find($logs['shop_id']);
                    D('Sms') -> sendSms('sms_ele', $member['mobile'], array('nickname' => $member['nickname'], 'shopname' => $shops['shop_name'], ));
                    D('Tongji') -> log(3, $logs['need_pay']);
                    //统计
                    //通知商家
                    D('Sms') -> eleTZshop($logs['order_id']);
                } elseif ($logs['type'] == 'assetRecharge') {//资产充值
                    $member =  $usersModel-> find($logs['user_id']);
                    $assetModel = D('UserAssetLogs');
                    $data = array(
                        'user_id' => $logs['user_id'],
                        'money' => $logs['need_pay'],
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产充值',
                        'type' => 1,//资产充值
                    );
                    $assetModel->startTrans();
                    if ($assetModel->add($data))
                    {
                        if ($usersModel->where(array('user_id' => $member['user_id']))->setInc('asset', $logs['need_pay']))
                        {
                            $assetModel->commit();
                        }
                        else
                        {
                            $assetModel->rollback();
                        }
                    }
                } elseif ($logs['type'] == 'buyMember') {//购买会员
                    $member =  $usersModel-> find($logs['user_id']);
                    $assetModel = D('UserAssetLogs');
                    $data = array(
                        'user_id' => $logs['user_id'],
                        'money' => $logs['need_pay'],
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '购买会员',
                        'type' => 3,//购买会员
                    );
                    $assetModel->startTrans();
                    if ($assetModel->add($data))
                    {
                        $levelId = $logs['need_pay'] == 99900 ? 3 : 2 ;
                        if ($usersModel->where(array('user_id' => $member['user_id']))->setInc(array('asset' => $logs['need_pay'], 'level_id' => $levelId)))
                        {
                            $assetModel->commit();
                        }
                        else
                        {
                            $assetModel->rollback();
                        }
                    }
                } elseif ($logs['type'] == 'moneyRecharge') {//余额充值
                    $member = $usersModel -> find($logs['user_id']);

                } else {// 商城购物
					if (empty($logs['order_id']) && !empty($logs['order_ids'])) {//合并付款
						$order_ids = explode(',', $logs['order_ids']);
						D('Order') -> save(array('status' => 1), array('where' => array('order_id' => array('IN', $order_ids))));
						//通知商家
						D('Sms') -> mallTZshop($order_ids);
						$this -> mallSold($order_ids);
						$this -> mallPeisong($order_ids);
					} else {
//						D('Order') -> save(array('order_id' => $logs['order_id'], 'status' => 1));
//						$this -> mallPeisong(array($logs['order_id']));
//						$this -> mallSold($logs['order_id']);
                        D('Order') -> save(array('order_id' => $logs['order_id'], 'status' => 4, 'pay_time' => NOW_TIME));
                        $this->commission($logs['order_id']);
//                        $this -> mallPeisong(array($logs['order_id']));
//                        $this -> mallSold($logs['order_id']);

						//通知商家
//						D('Sms') -> mallTZshop($logs['order_id']);
					}
//					D('Tongji') -> log(2, $logs['need_pay']);
					//统计
				}
			}
		}

		return true;
	}

	//更新商城销售接口
	public function mallSold($order_ids) {
		if (is_array($order_ids)) {
			$order_ids = join(',', $order_ids);
			$ordergoods = D('Ordergoods') -> where("order_id IN ({$order_ids})") -> select();
			foreach ($ordergoods as $k => $v) {
				D('Goods') -> updateCount($v['goods_id'], 'sold_num', $v['num']);
			}
		} else {
			$order_ids = (int)$order_ids;
			$ordergoods = D('Ordergoods') -> where('order_id =' . $order_ids) -> select();
			foreach ($ordergoods as $k => $v) {
				D('Goods') -> updateCount($v['goods_id'], 'sold_num', $v['num']);
			}
		}
		return TRUE;
	}

	//配送接口
	public function mallPeisong($order_ids) {

		foreach ($order_ids as $order_id) {

			$order = D('Order') -> where('order_id =' . $order_id) -> find();

			$shops = D('Shop') -> find($order['shop_id']);
			if ($shops['is_pei'] == 0) {
				$member = D('Users') -> find($order['user_id']);
				$ua = D('UserAddr');
				$dv = D('DeliveryOrder');
				$uaddr = $ua -> where('user_id =' . $order['user_id']) -> find();

				//在线支付成功以后，进入配送员判断

				$dv_data = array('type' => 0, 'type_order_id' => $order['order_id'], 'delivery_id' => 0, 'shop_id' => $order['shop_id'], 'user_id' => $order['user_id'], 'shop_name' => $shops['shop_name'], 'shop_addr' => $shops['addr'], 'shop_mobile' => $shops['tel'], 'user_name' => $member['nickname'], 'user_addr' => $uaddr['addr'], 'user_mobile' => $member['mobile'], 'create_time' => time(), 'update_time' => 0, 'status' => 1);

				$dv -> add($dv_data);
			}

			//通知商家
			D('Sms') -> mallTZshop($logs['order_id']);
		}
		D('Tongji') -> log(2, $logs['need_pay']);
		//统计

		return true;
	}

	public function checkPayment($code) {
		$datas = $this -> fetchAll();
		foreach ($datas as $val) {
			if ($val['code'] == $code)
				return $val;
		}
		return array();
	}

	public function getPayment($code) {
		$datas = $this -> fetchAll();
		foreach ($datas as $val) {
			if ($val['code'] == $code)
				return $val['setting'];
		}
		return array();
	}

    public function commission($orderId)
    {
        $city = 0.1;
        $gold = 0.45;
        $diamond = 0.8;
        $orderModel = D('Order');
        $usersModel = D('Users');
        $moneyModel = D('Usermoneylogs');
        $assetModel = D('UserAssetLogs');
        $order = $orderModel->field('shop_id,user_id,total_price,original_price,precent')->find($orderId);
        $proprotion = $order['original_price'] * ($order['precent'] / 100);
        if (!empty($order['shop_id']))
        {
            $shopData = array(
                'shop_id' => $order['shop_id'],
                'money' => $order['original_price'] - $proprotion,//商家结算
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip(),
                'intro' => '',
                'status' => 2,
                'type' => 2,//
                'audit' => 0,
                'order_id' =>$orderId
            );
            D('ShopMoneyLogs')->add($shopData);
            /*$cityId = D('Shop')->field('city_id')->find($order['shop_id']);
            if (!empty($cityId['city_id']))
            {
                $cityData = array(
                    'city_id' => $cityId['city_id'],
                    'money' => $proprotion * $city,//城市结算
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'intro' => '',
                    'status' => 2,
                    'type' => 2,//
                    'audit' => 0,
                    'order_id' =>$orderId
                );
                D('CityMoneyLogs')->add($cityData);
            }*/
        }

        $userInfo = $usersModel->field('user_id,level_id,pid')->find($order['user_id']);
        $parentInfo = $usersModel->getParentInfo($order['user_id']);
        $grandInfo = $usersModel->getParentInfo($parentInfo['user_id']);
//        var_dump($userInfo,$parentInfo,$grandInfo);die();
        if ($userInfo['level_id'] == 1) //  消费者是普通
        {
            if ($parentInfo['level_id'] == 2) //    上级是黄金
            {
                if ($parentInfo['asset'] > $order['total_price'])
                {
                    $assetData = array(
                        'user_id' => $parentInfo['user_id'],
                        'money' => $order['original_price'] - ($proprotion * $gold),//全额变现
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产变现',
                        'status' => 1,
                        'type' => 6,//资产变现
                        'order_id' =>$orderId
                    );
                    $moneyModel->add($assetData);
                    $assetLog = array(
                        'user_id' => $parentInfo['user_id'],
                        'money' => $order['original_price'] - ($proprotion * $gold),//全额变现
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产变现',
                        'type' => 2,//资产变现
                        'order_id' =>$orderId
                    );
                    $assetModel->add($assetLog);
                }
                $intoData = array(
                    'user_id' => $parentInfo['user_id'],
                    'money' => $proprotion * $gold,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'intro' => '消费分成',
                    'status' => 1,
                    'type' => 5,//分成
                    'order_id' =>$orderId
                );
                $moneyModel->add($intoData);
                if ($grandInfo['level_id'] == 3)
                {
                    $intoData = array(
                        'user_id' => $grandInfo['user_id'],
                        'money' => $proprotion * ($diamond - $gold),
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '消费分成',
                        'status' => 1,
                        'type' => 5,//分成
                        'order_id' =>$orderId
                    );
                    $moneyModel->add($intoData);
                }
            }
            elseif ($parentInfo['level_id'] == 3)   //  上级是钻石
            {
                if ($parentInfo['asset'] > $order['total_price'])
                {
                    $assetData = array(
                        'user_id' => $parentInfo['user_id'],
                        'money' => $order['original_price'] - ($proprotion * $diamond),//全额变现
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产变现',
                        'status' => 1,
                        'type' => 6,//资产变现
                        'order_id' =>$orderId
                    );
                    $moneyModel->add($assetData);
                    $assetLog = array(
                        'user_id' => $parentInfo['user_id'],
                        'money' => $order['original_price'] - ($proprotion * $diamond),//全额变现
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产变现',
                        'type' => 2,//资产变现
                        'order_id' =>$orderId
                    );
                    $assetModel->add($assetLog);
                }
                $intoData = array(
                    'user_id' => $parentInfo['user_id'],
                    'money' => $proprotion * $diamond,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'intro' => '消费分成',
                    'status' => 1,
                    'type' => 5,//分成
                    'order_id' =>$orderId
                );
                $moneyModel->add($intoData);
            }
        }
        elseif ($userInfo['level_id'] == 2) //  消费者是黄金
        {
            if ($parentInfo['level_id'] == 3)   //  上级是钻石
            {
                if ($parentInfo['asset'] > $order['total_price'])
                {
                    $assetData = array(
                        'user_id' => $parentInfo['user_id'],
                        'money' => $order['original_price'] - ($proprotion * ($diamond - $gold)),//全额变现
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产变现',
                        'status' => 1,
                        'type' => 6,//资产变现
                        'order_id' =>$orderId
                    );
                    $moneyModel->add($assetData);
                    $assetLog = array(
                        'user_id' => $parentInfo['user_id'],
                        'money' => $order['original_price'] - ($proprotion * ($diamond - $gold)),//全额变现
                        'create_time' => NOW_TIME,
                        'create_ip' => get_client_ip(),
                        'intro' => '资产变现',
                        'type' => 2,//资产变现
                        'order_id' =>$orderId
                    );
                    $assetModel->add($assetLog);
                }
                $intoData = array(
                    'user_id' => $parentInfo['user_id'],
                    'money' => $proprotion * ($diamond - $gold),
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'intro' => '消费分成',
                    'status' => 1,
                    'type' => 5,//分成
                    'order_id' =>$orderId
                );
                $moneyModel->add($intoData);
            }
        }
        elseif ($userInfo['level_id'] == 3) //  消费者是钻石
        {
        }
	}

//    public function _commission($userLevel, $parentLevel)
//    {
//        switch ($parentLevel)
//        {
//            case 1:
//                break;
//            case 2:
//                if ($userLevel == 1)
//                break;
//            case 3:
//                break;
//        }
//	}

}
