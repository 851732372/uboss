<?php

/**
 * 商品
 * Class GoodsAction
 */
class GoodsAction extends BaseAction
{
    /**
     * 商品信息
     * @author Ginger
     * return
     */
    public function goods()
    {
        if ($this->isPost())
        {
            $params = $this->_post();
            if (isset($params['id']) && (int)$params['id'] != 0)
            {
//                return outMessage(1,'商品详情接口');
                $this->goodsDetail($params['id']);
            }
            elseif (isset($params['shopId']) && (int)$params['shopId'] != 0)
            {
                $where['g.shop_id'] = $params['shopId'];
                $where['g.closed'] = 0;
                $where['g.audit'] = 1;
                $goodsModel = D('Goods g');
                $list = $goodsModel
                    ->field('g.goods_id id, g.title name,g.mall_price/100 price')
                    ->field('g.goods_id id,g.shop_id shopId,g.title name,g.`mall_price`/100 price,g.photo img,s.shop_name shopName,s.lat,s.lng,b.business_name position,g.details,g.instructions label,g.standard,g.details,s.proportions')
                    ->join('uboss_shop s ON s.shop_id = g.shop_id')
                    ->join('uboss_business b ON b.business_id = g.business_id')
                    ->where($where)
//                    ->order('views desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
                foreach ($list as $key => $val)
                {
                    $list[$key]['label'] = explode('|', $val['label']);
                    $list[$key]['informations'] = $this->_option(unserialize($val['standard']));
                    $list[$key]['tips'] = $this->_option(unserialize($val['details']));
                    unset($list[$key]['standard'], $list[$key]['details']);
                }
                return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
            }
            else
            {
                return outMessage(-1, '非法请求，参数错误');
            }
        }
        else
        {
            return outMessage(-1, '非法请求');
        }
    }

    /**
     * 商品详情
     * @author Ginger
     * @param $id
     * return
     */
    private function goodsDetail($id)
    {
        $goodsModel = D('Goods g');
        $goodsModel->where(array('goods_id' => $id))->setInc('views');
        $goodsInfo = $goodsModel
            ->field('g.goods_id id,g.shop_id shopId,g.title name,g.`mall_price`/100 price,g.photos img,g.photo picture,s.shop_name shopName,s.lat,s.lng,b.business_name position,g.details,g.instructions label,g.standard,g.details,s.proportions,g.`price`/100 storePrice')
            ->join('uboss_shop s ON s.shop_id = g.shop_id')
            ->join('uboss_business b ON b.business_id = g.business_id')
            ->where("g.goods_id = {$id}")
            ->find();
        $goodsInfo['img'] = explode(',', trim($goodsInfo['img'],','));;
        $goodsInfo['label'] = explode('|', $goodsInfo['label']);
        $goodsInfo['informations'] = $this->_option(unserialize($goodsInfo['standard']));
        $goodsInfo['tips'] = $this->_option(unserialize($goodsInfo['details']));
        unset($goodsInfo['standard'], $goodsInfo['details']);
        return outJson($goodsInfo);
    }
}