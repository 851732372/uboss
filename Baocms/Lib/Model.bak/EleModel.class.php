<?php
class EleModel extends CommonModel{
    protected $pk   = 'shop_id';
    protected $tableName =  'ele';
    
    public function  updateMonth($shop_id){
        $shop_id = (int)$shop_id;
        $month = date('Ym',NOW_TIME);
        $num = (int)(D('Eleorder')->where(array('shop_id'=>$shop_id,'month'=>$month))->count());
        return $this->execute("update ".$this->getTableName()." set  month_num={$num} where shop_id={$shop_id}");
    }

    public function getEleCate() {
        return array(
            '1' => '快餐简餐',
            '2' => '正餐',
            '3' => '馋嘴小吃',
            '4' => '甜点饮料',
            '5' => '生活超市',
            '6' => '水果蔬菜',
        );
    }

}
