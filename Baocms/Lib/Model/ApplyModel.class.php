<?php

/**
 * File name: ApplyModel.class.php
 * 文件描述
 * Created on: 2018/10/9 14:16
 * Created by: Ginger.
 */
class ApplyModel extends CommonModel
{
    protected $pk   = 'apply_id';
    protected $tableName =  'apply';
    
    /**
     * 检测是否已经提交过信息
     * @author Ginger
     * @param $user_id
     * @param $shop_id
     * return
     */
    public function check($user_id,$shop_id){
        $data = $this->find(array('where'=>array('shop_id'=>(int)$shop_id,'user_id'=>(int)$user_id)));
        return $this->_format($data);
    }

    /**
     * 获取当前优店职位人数
     * @author Ginger
     * @param $shop_id
     * @param $apply_position
     * return
     */
    public function getPositionNum($shop_id, $apply_position)
    {
        return $this->where(array('shop_id'=>(int)$shop_id,'apply_position'=>(int)$apply_position))->count();
    }

}