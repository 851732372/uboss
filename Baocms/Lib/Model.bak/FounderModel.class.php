<?php

/**
 * File name: FounderModel.class.php
 * 创始人模型
 * Created on: 2018/9/23 17:44
 * Created by: Ginger.
 */
class FounderModel extends CommonModel
{
    protected $pk   = 'founder_id';
    protected $tableName =  'founder';


    public function getStoreType($id)
    {
        $store = $this->field('store_type')->find($id);
        return $store['store_type'];
    }
}