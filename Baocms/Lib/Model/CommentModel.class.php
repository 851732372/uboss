<?php

/**
 * File name: CommentModel.class.php
 * 文件描述
 * Created on: 2018/10/13 18:34
 * Created by: Ginger.
 */
class CommentModel extends CommonModel
{
    protected $pk   = 'comment_id';
    protected $tableName =  'comment';

    /**
     * 店铺评分
     * @author Ginger
     * @param $shopId
     * return
     */
    public function shopScore($shopId)
    {
        $commentModel = D('Comment');
        $where['shop_id'] = $shopId;
        $where['status'] = 2;
//        $where['create_time'] = ;
        $count = $commentModel->where($where)->count('comment_id');
        $shopStarts = $commentModel->where($where)->sum('shop_starts');
        unset($where['status']);
        $score = sprintf('%.2f',$shopStarts/$count);
        if (D('Shop')->where($where)->setField(array('score' => $score, 'score_num' => $count)))
        {
            return true;
        }
        return false;
    }
}