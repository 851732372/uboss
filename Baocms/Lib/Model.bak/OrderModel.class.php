<?php



class OrderModel extends CommonModel {

    protected $pk = 'order_id';
    protected $tableName = 'order';
    protected $types = array(
        0 => '等待付款',
        1 => '等待发货',
        2 => '仓库已捡货',
        8 => '已完成配送',
    );

    public function getType() {
        return $this->types;
    }

    public function overOrder($order_id) {
        $order = D('Order')->find($order_id);
        if (empty($order))
            return false;
        if ($order['status'] != 2)
            return false;
        if (D('Order')->save(array('status' => 8, 'order_id' => $order_id))) {
            $userobj = D('Users');
            $goods = D('Ordergoods')->where(array('order_id' => $order_id))->select();
            $shop = D('Shop')->find($order['shop_id']);
            if (!empty($goods)) {
                D('Ordergoods')->save(array('status' => 8), array('where' => array('order_id' => $order_id)));
                if ($order['is_daofu'] == 0) {
                    $ip = get_client_ip();
                    foreach ($goods as $val) {
                        $money = $val['js_price'];
                        if ($val['tui_uid']) { //推广员分成
                            $gooddetail = D('Goods')->find($val['goods_id']);
                            if (!empty($gooddetail['commission']) && $gooddetail['commission'] < $gooddetail['mall_price'] && $gooddetail['commission'] < $val['js_price']) { //小于的情况下才能返利不然你懂的
                                $money -= $gooddetail['commission'];
                                D('Users')->addMoney($val['tui_uid'], $gooddetail['commission'], '推广佣金');
                                $info.='扣除了佣金' . round($gooddetail['commission'] / 100, 2);
                            }
                        }
                        if ($money > 0) {
                            D('Shopmoney')->add(array(
                                'shop_id' => $order['shop_id'],
                                'money' => $money,
                                'create_time' => NOW_TIME,
                                'create_ip' => $ip,
                                'type' => 'goods',
                                'order_id' => $order_id,
                                'intro' => $info,
                            ));

                            D('Users')->addMoney($shop['user_id'], $money, $info);
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function money($bg_time, $end_time, $shop_id) {
        $bg_time = (int) $bg_time;
        $end_time = (int) $end_time;
        $shop_id = (int) $shop_id;
        if (!empty($shop_id)) {
            $data = $this->query(" SELECT sum(total_price)/100 as price,FROM_UNIXTIME(create_time,'%m%d') as d from  " . $this->getTableName() . "   where status=8 AND create_time >= '{$bg_time}' AND create_time <= '{$end_time}' AND shop_id = '{$shop_id}'  group by  FROM_UNIXTIME(create_time,'%m%d')");
        } else {
            $data = $this->query(" SELECT sum(total_price)/100 as price,FROM_UNIXTIME(create_time,'%m%d') as d from  " . $this->getTableName() . "   where status=8 AND create_time >= '{$bg_time}' AND create_time <= '{$end_time}'  group by  FROM_UNIXTIME(create_time,'%m%d')");
        }
        $showdata = array();
        $days = array();

        for ($i = $bg_time; $i <= $end_time; $i+=86400) {
            $days[date('md', $i)] = '\'' . date('m月d日', $i) . '\'';
        }
        $price = array();
        foreach ($days as $k => $v) {
            $price[$k] = 0;
            foreach ($data as $val) {
                if ($val['d'] == $k) {
                    $price[$k] = $val['price'];
                }
            }
        }
        $showdata['d'] = join(',', $days);
        $showdata['price'] = join(',', $price);
        return $showdata;
    }

}
