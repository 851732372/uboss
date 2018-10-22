<?php

/**
 * File name: DividemoneyModel.class.php
 * 文件描述
 * Created on: 2018/10/9 13:49
 * Created by: Ginger.
 */
class DividemoneyModel extends CommonModel
{
    protected $pk   = 'divide_id';
    protected $tableName =  'divide_money';

    public function getMoney($shopId)
    {
        $data = $this->find(array('where'=>array('shop_id'=>$shopId)));
        return $this->_format($data);
    }
}