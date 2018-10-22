<?php
/*
 * 商户管理
 * 作者：liuqiang
 * 日期: 2018/9/30
 */
class IndexAction extends CommonAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->order = M('order');
        $this->shop = M('shop');
        $this->shop_money_logs = M('shop_money_logs');
    }
    public function index()
    {
        $menu = D('Menu')->where('is_del = 1')->select();
        $m = array();
        foreach ($menu as $key => $value) {
            $m[$value['menu_id']] = $value;
        }
        if ($this->_admin['role_id'] != 1) {
            if ($this->_admin['menu_list']) {
                foreach ($m as $k => $val) {
                    if (!empty($val['menu_action']) && !in_array($k,$this->_admin['menu_list'])) {
                        unset($m[$k]);
                    }
                } 
                foreach ($m as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        foreach ($m as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = true;
                                foreach ($m as $k3 => $v3) {
                                    if ($v3['parent_id'] == $v2['menu_id']) {
                                        $unset = false;
                                    }
                                }
                                if ($unset)
                                    unset($m[$k2]);
                            }
                        }
                    }
                }
                foreach ($m as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        $unset = true;
                        foreach ($m as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = false;
                            }
                        }
                        if ($unset)
                            unset($m[$k1]);
                    }
                }
            }else {
                $m = array();
            }
        }
        $this->assign('menuList',$m);
      // 管理城市
      // 城市
        $city = M('city')
                ->where('city_id = '.$this->_admin['city_id'])
                ->field('name')
                ->find();
        $areaname = $city['name'] ? $city['name'] : '全部';
        $this->assign('areaname',$areaname);
        $this->display();
    }
     
    public function main(){
        // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
        // 总营收
        $allmoney = $this->shop_money_logs->alias('o')
                    ->join('uboss_shop s on s.shop_id = o.shop_id')
                    ->where($map)
                    ->field('sum(o.money) price')
                    ->find();
        $money = floor($allmoney['price'])/100;
        $this->assign('allmoney',$money);
        // 今日时间
        $begin = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        // 今日订单
        $map['_string'] = "o.create_time <= $end and o.create_time >= $begin";
        $totay_order = $this->order->alias('o')
                        ->join('uboss_shop s on s.shop_id = o.shop_id')
                        ->where($map)
                        ->count();
                        
        $this->assign('totay_order',$totay_order);
        // 今日新增商家
        $map['_string'] = "create_time <= $end and create_time >= $begin and founder_id = 0";
        $totay_shop = (int)$this->shop->where($map)->count();
        $this->assign('totay_shop',$totay_shop);
        // 今日新增U店
        $map['_string'] = "create_time <= $end and create_time >= $begin and founder_id != 0";
        $totay_u = (int)$this->shop->where($map)->count();
        $this->assign('totay_u',$totay_u);
        // 商家入驻申请
        unset($map['_string']);
        $shop_apply = M('shop_apply')->alias('s')
                      ->where($map)
                      ->count();
        $this->assign('shop_apply',$shop_apply);
        // U店入驻申请
        $founder = M('founder')->alias('f')
                   ->join('uboss_shop s on s.shop_id = f.shop_id')
                   ->where($map)
                   ->where('f.status = 1')
                   ->count();
        $this->assign('founder',$founder);
        // 商品上架
        $goods = M('goods')->alias('g')
                ->join('uboss_shop s on s.shop_id = g.shop_id')
                ->where($map)
                ->where('g.is_shelf = 1')
                ->count();
        $this->assign('goods',$goods);
        // 商家提现待审核
        $shop_money_logs = M('shop_money_logs')->alias('l')
                           ->join('uboss_shop s on s.shop_id = l.shop_id')
                           ->where($map)
                           ->where('l.type = 1 and l.status = 0')
                           ->count();
        $this->assign('shop_money_logs',$shop_money_logs);
        // 用户提现待审核
        $user_money_logs = M('user_money_logs')
                           ->where('type = 3 and status = 1')
                           ->count();
        $this->assign('user_money_logs',$user_money_logs);
        // 城市提现待审核
        $city_money_logs = M('city_money_logs')->alias('l')
                           ->join('uboss_shop s on s.shop_id = l.shop_id')
                           ->where($map)
                           ->where('l.type = 1 and l.status = 0')
                           ->count();
        $this->assign('city_money_logs',$city_money_logs);
        // 会员总人数
        $vip = M('users')->where('level_id > 1')->count();
        $this->assign('vip',$vip);
        // 会员认证待审核
        $dvip = M('users')->where('is_reg = 0')->count();
        $this->assign('dvip',$dvip);
         // 今日注册量
        $totay_l = (int)M('users')->where(array(
            'reg_time'=> array(
                array('ELT',$end),
                array('EGT',$begin),
            ),
        ))->count();
        $this->assign('totay_l',$totay_l);
         // 今日新增会员
        $totay_v = (int)M('users')->where(array(
            'reg_time'=> array(
                array('ELT',$end),
                array('EGT',$begin),
            ),
            'is_reg' => array('eq',1),
        ))->count();
        $this->assign('totay_v',$totay_v);
        $this->display();
    }
     
    public function check(){ //后期获得通知使用！
         die('1');
    }
     // 订单总数 30天
    public function ajax_select_d(){
        $begin = mktime(0,0,0,date('m'),1,date('Y'));
        $end = mktime(23,59,59,date('m'),date('t'),date('Y'));
         // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
       
        $res = M('order')->alias('o')
            ->join('uboss_shop s on s.shop_id = o.shop_id')
            ->where($map)
            ->order('o.create_time asc')
            ->group('FROM_UNIXTIME(o.create_time,"%m-%d")')
            ->field('FROM_UNIXTIME(o.create_time,"%m-%d") date,count(*) num')
            ->select();
        echo outJson($res);
    }
}