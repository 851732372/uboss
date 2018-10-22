<?php

/*
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.taobao.com
 * 邮件: youge@baocms.com  QQ 800026911
 */

class IndexAction extends CommonAction {
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = M('order');
    }
    public function index() {
        $this->display();
    }

    public function main() {
        // 今日收入
        $bg_time = strtotime(TODAY);
        $all_money = $this->sql->where(array(
                    'shop_id' => $this->shop_id,
                    'create_time' => array(
                        array('ELT', NOW_TIME),
                        array('EGT', $bg_time),
                    ), 'status' => array(
                        'GT', 0
                    ),
                ))->sum('total_price');
        $this->assign('all_money',$all_money/100);

        // 折线图
        
        $this->display();
    }

}
