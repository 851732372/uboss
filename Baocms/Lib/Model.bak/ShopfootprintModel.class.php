<?php


class ShopfootprintModel extends CommonModel{
    protected $pk   = 'footprint_id';
    protected $tableName =  'shop_footprint';

    public function check($goods_id,$user_id, $closed = 0){
        $data = $this->find(array('where'=>array('shop_id'=>(int)$goods_id,'user_id'=>(int)$user_id)));
        return $this->_format($data);
    }

}