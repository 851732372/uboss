<?php
/*
 * 营业管理
 * 作者：liuqiang
 * 日期: 2018/9/17
 */
class CensusAction extends CommonAction 
{
	public function _initialize()
    {
        parent::_initialize();
       
    }
	public function index()
	{
		$this->display();
	}
    // 交易分析
    public function trade()
    {
        // 订单类型  日期  金额
        $this->display();
    }
    public function ajax_quest()
    {
        $map['shop_id'] = $this->shop_id;
        $begin = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
        $end = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
        $map['_string'] = "(create_time>= $begin AND create_time<= $end)";
        $data = M('order')
                ->where($map)
                ->order('create_time asc')
                ->group('FROM_UNIXTIME(create_time,"%Y-%m-%d")')
                ->field('FROM_UNIXTIME(create_time,"%Y-%m-%d") date,type,sum(total_price)/100 price')
                ->select();
        foreach ($data as $key => $value) {
            if($value['type'] == 1){
                $data[$key]['type'] = '线上支付';
            }
            if($value['type'] == 2){
                $data[$key]['type'] = '扫码支付';
            }
        }
        echo outJson($data);
    }
    public function ajax_quest_d()
    {
        $type = $this->_param('type');
        $map['shop_id'] = $this->shop_id;
        if($type == 1){
            $begin = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $end = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
        }
        if($type == 2){
            $begin = mktime(0,0,0,date('m'),1,date('Y'));
            $end = mktime(23,59,59,date('m'),date('t'),date('Y'));
        }
        $map['_string'] = "(create_time >= $begin AND create_time <= $end)";
        $data = M('order')
                ->where($map)
                ->order('create_time asc')
                ->group('FROM_UNIXTIME(create_time,"%Y-%m-%d")')
                ->field('FROM_UNIXTIME(create_time,"%Y-%m-%d") date,type,sum(total_price)/100 price')
                ->select();
        foreach ($data as $key => $value) {
            if($value['type'] == 1){
                $data[$key]['type'] = '线上支付';
            }
            if($value['type'] == 2){
                $data[$key]['type'] = '扫码支付';
            }
        }
        echo outJson($data);
    }
    // 交易明细
    public function trade_detail()
    {
        $this->display();
    }
    // 营业统计
    public function ajax_select()
    {
        $map['shop_id'] = $this->shop_id;
        $res = M('order')
                ->where($map)
                ->order('create_time asc')
                ->group('FROM_UNIXTIME(create_time,"%k")')
                ->field('FROM_UNIXTIME(create_time,"%k") date,count(*) num,sum(total_price)/100 price')
                ->select();
        foreach ($res as $key => $value) {
           $res[$key]['date'] = $value['date'].':00';
        }
        echo outJson($res);
    }
    public function select_d()
    {
        $map['shop_id'] = $this->shop_id;
        $type = $this->_param('type');
        // 获取今日开始时间戳和结束时间戳
        if($type != 0){
            if($type == 1){
                $begin = mktime(0,0,0,date('m'),date('d'),date('Y'));
                $end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
                $map['_string'] = "(create_time>= $begin AND create_time<= $end)";
                $res = M('order')
                    ->where($map)
                    ->order('create_time asc')
                    ->group('FROM_UNIXTIME(create_time,"%k")')
                    ->field('FROM_UNIXTIME(create_time,"%k") date,count(*) num,sum(total_price)/100 price')
                    ->select();
                foreach ($res as $key => $value) {
                   $res[$key]['date'] = $value['date'].':00';
                }
            }
            // 获取昨日起始时间戳和结束时间戳
            if($type == 2){
                $begin = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
                $end = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
                $map['_string'] = "(create_time >= $begin AND create_time<= $end)";
                $res = M('order')
                    ->where($map)
                    ->order('create_time asc')
                    ->group('FROM_UNIXTIME(create_time,"%k")')
                    ->field('FROM_UNIXTIME(create_time,"%k") date,count(*) num,sum(total_price)/100 price')
                    ->select();
                foreach ($res as $key => $value) {
                   $res[$key]['date'] = $value['date'].':00';
                }
            }
            // 获取上周起始时间戳和结束时间戳
            if($type == 3){
                $begin = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
                $end = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
                $map['_string'] = "(create_time >= $begin AND create_time<= $end)";
                $res = M('order')
                    ->where($map)
                    ->order('create_time asc')
                    ->group('FROM_UNIXTIME(create_time,"%M-%d")')
                    ->field('FROM_UNIXTIME(create_time,"%M-%d") date,count(*) num,sum(total_price)/100 price')
                    ->select();
            }
            // 获取本月起始时间戳和结束时间戳
            if($type == 4){
                $begin = mktime(0,0,0,date('m'),1,date('Y'));
                $end = mktime(23,59,59,date('m'),date('t'),date('Y'));
                $map['_string'] = "(create_time>= $begin AND create_time<= $end)";
                $res = M('order')
                    ->where($map)
                    ->order('create_time asc')
                    ->group('FROM_UNIXTIME(create_time,"%m-%d")')
                    ->field('FROM_UNIXTIME(create_time,"%m-%d") date,count(*) num,sum(total_price)/100 price')
                    ->select();
            }
        }else{
            $res = M('order')
                    ->where($map)
                    ->order('create_time asc')
                    ->group('FROM_UNIXTIME(create_time,"%k")')
                    ->field('FROM_UNIXTIME(create_time,"%k") date,count(*) num,sum(total_price)/100 price')
                    ->select();
            foreach ($res as $key => $value) {
               $res[$key]['date'] = $value['date'].':00';
            }
        }
       
        echo outJson($res);
    }
}