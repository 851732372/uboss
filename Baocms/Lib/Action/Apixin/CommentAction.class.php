<?php

/**
 * File name: CommentgoodsAction.class.php
 * 评价商品
 * Created on: 2018/9/26 18:00
 * Created by: Ginger.
 */
class CommentAction extends BaseAction
{
    public function comment()
    {
        if ($this->isPost())
        {
            $type = $this->_post('type');
            if ($type == 'createComment')    //  新增评论
            {
                $this->createComment();
            }
            elseif ($type == 'list')    //  评论列表
            {
                $this->lists();
            }
            else
            {
                return outMessage(-1,'非法请求，参数错误');
            }
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }

    /**
     * 添加评论
     * @author Ginger
     * return
     */
    private function createComment()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $orderId = (int) $this->_post('orderId');
            $shopId = (int) $this->_post('shopId');
            $goodsId = (int) $this->_post('goodsId');
            $goodsStart = (int) $this->_post('goodsScore');
            $content = htmlspecialchars($this->_post('content'));
            $content =$this->_checkWords($content);

            $shopStart = (int) $this->_post('shopScore');
            $pictures = $this->_post('pictures');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            if (!is_array($pictures)) return outMessage(-1, '参数格式错误');
            $orderModel = D('Order');
            $orderGoodsModel = D('OrderGoods');
            $order = $orderModel->field('shop_id,status')->where('status = 7')->find($orderId);
            $orderGoods = $orderGoodsModel->field('id,shop_id,goods_id,is_comment')->where("is_comment = 0 and order_id = {$orderId}")->find();
            if (empty($orderGoods) || empty($order)) return outMessage(-1, '该订单不存在或已评价');
            D('Sensitive')->checkWords($content);
            $images = array();
            foreach ($pictures as $v)
            {
                $images[] = base64_image_content($v, 'comment');
            }
            if (empty($images)) return outMessage(-1,'评论提交失败，请稍后重试');
            $data = array(
                'user_id' => $userId,
                'order_id' => $orderId,
                'shop_id' => $orderGoods['shop_id'],
                'goods_id' => $orderGoods['goods_id'],
                'content' => $content,
                'comment_starts' => $goodsStart,
                'comment_img' => json_encode($images),
                'shop_starts' => $shopStart,
                'status' => 1,
                'create_time' => NOW_TIME
            );
            $commentModel = D('Comment');
            $commentModel->startTrans();
            if ($commentModel->add($data) && $orderGoodsModel->where("id = {$orderGoods['id']}")->setField('is_comment', 1) && $orderModel->where("order_id = {$orderId}")->setField('status', 8))
            {
                $commentModel->commit();
                return outMessage(1, '评论成功，谢谢您的宝贵意见');
            }
            else
            {
                $commentModel->rollback();
                return outMessage(-1, '提交失败，请稍后重试');
            }
        }
    }

    /**
     * 根据店铺获取评论列表
     * @author Ginger
     * return
     */
    private function lists()
    {
        if ($this->isPost())
        {
            $shopId = (int) $this->_post('shopId');
            if (!$shopId) return outMessage(-1, 'shopId不能为空');
            $where['c.status'] = 2;
            $where['c.shop_id'] = array('eq', $shopId);
            $list = D('Comment c')
                ->field('c.comment_id id,u.face portrait,u.nickname userName,c.create_time time,c.comment_starts score,c.content,c.comment_img pictures')
                ->join('uboss_users u ON u.user_id = c.user_id')
                ->where($where)
                ->order('c.create_time DESC')
                ->page($this->page, $this->pageSize)
                ->select();
            foreach ($list as $key => $val)
            {
//                $list[$key]['content'] = $this->_checkWords($val['content']);
                $list[$key]['pictures'] = json_decode($val['pictures'], true);
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }
}