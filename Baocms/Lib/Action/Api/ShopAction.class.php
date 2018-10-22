<?php

/**
 * 商家
 * Class ShopAction
 */
class ShopAction extends BaseAction
{
    /**
     * 商家列表
     * @author Ginger
     * @param
     * return
     */
    public function shop()
    {
        $lng = session('location.lng');
        $lat = session('location.lat');
        if ($this->isPost())
        {
            $params = $this->_post();
            if (isset($params['id']) && (int)$params['id'] != 0)
            {
//                $this->price($params['id']);
//                $this->perPrice($params['id']);
                $this->shopDetail($params['id']);
            }
            elseif (isset($params['typeId']) && (int)$params['typeId'] != 0)
            {
                $distance = false;
                $where['city_id'] = session('cityId');
                $where['founder_id'] = 0;
                $where['closed'] = 0;
                if (isset($params['recommend']) && $params['recommend']) $orderBy = "views desc";
                if (isset($params['distance']) && $params['distance'])
                {
                    $orderBy = "distance ASC";
                    $distance = true;
                }
                if (isset($params['score']) && $params['score']) $orderBy = "score desc";
                if (isset($params['ushopSelect']) && $params['ushopSelect']) $where['founder_id'] = array('neq', 0);
                $cateIds = D('Shopcate')->getChildren($params['typeId']);
                $where['cate_id'] = array('IN',join(',', $cateIds));
                $shopModel = D('Shop');
                $list = $shopModel
                    ->field("shop_id id,shop_name name,addr address,lat,lng,price/100 price,photo picture,CONCAT(FORMAT((2 * 6378.137* ASIN(SQRT(POW(SIN(3.1415926535898*({$lat}-lat)/360),2)+COS(3.1415926535898*{$lat}/180)* COS(lat * 3.1415926535898/180)*POW(SIN(3.1415926535898*({$lng}-lng)/360),2)))),2)) as distance,proportions")
                    ->where($where)
                    ->order($orderBy)
                    ->page($this->page, $this->pageSize)
                    ->select();

                if ($distance) array_multisort(array_map(create_function('$n', 'return $n["distance"];'), $list),SORT_ASC, $list );
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
     * 商家详情
     * @author Ginger
     * @param $id 商家id
     * return
     */
    private function shopDetail($id)
    {
        $lng = session('location.lng');
        $lat = session('location.lat');
        $shopModel = D('Shop');
        $shopInfo = $shopModel
            ->field('shop_id id,shop_name name,cate_id,score,addr address,tel,bussiness_time time,shop_mark label,lat,lng,logo,otherimgs pictures,is_enough')
            ->find($id);
        $shopInfo['label'] = explode('/', $shopInfo['label']);
        $shopInfo['pictures'] = explode(',', trim($shopInfo['pictures'], ','));
        //array_filter(explode(',', $shopInfo['pictures']));//$shopModel->getphoto($id, '',6);
        $shopInfo['distance'] = getDistance($shopInfo['lat'], $shopInfo['lng'], $lat, $lng);
        $favorites = D('Shopfavorites')->check($id, session('userInfo.user_id'));
        $shopInfo['is_favorites'] = (empty($favorites) || $favorites['closed'] == 1) ? 0 : 1 ;
        $shopInfo['categoryName'] = D('Shopcate')->getCateName($shopInfo['cate_id']);
        unset($shopInfo['cate_id']);
        return outJson($shopInfo);
    }

    /**
     * 商家推荐图
     * @author Ginger
     * return
     */
    public function recommendPictures()
    {
        if ($this->isPost())
        {
            $typeId = (int) $this->_post('typeId');
            if (!$typeId) return outMessage(-1, '分类id不能为空');
            $count = D('ShopCate')->where(array('parent_id' => 0, 'cate_id' => $typeId))->count();
            if ($count != 1) return outMessage(-1, '必须是一级分类id');
            $shopModel = D('Shop');
            $where['closed'] = 0;
            $where['cate_id'] = $typeId;
            $where['is_recom'] = 1;
            $data = $shopModel->field('shop_id shopId,recomimgs recommendPictures')->where($where)->page($this->page, $this->pageSize)->select();
            return outJson($data);
        }
        else
        {
            return outMessage(-1, '非法请求');
        }
    }

    /**
     * 分类轮播图
     * @author Ginger
     * return
     */
    public function typePictures()
    {
        if ($this->isPost())
        {
            $typeId = (int) $this->_post('typeId');
            if (!$typeId) return outMessage(-1, '分类id不能为空');
            $shopCateModel = D('ShopCate');
            $where = array('parent_id' => 0, 'cate_id' => $typeId);
            $data = $shopCateModel->field('cate_id typeId,carousel typePictures')->where($where)->find();
            if (empty($data)) return outMessage(-1, '不是一级分类');
            $data['typePictures'] = trim($data['typePictures'], ',');;
            $data['typePictures'] = explode(',', $data['typePictures']);
            return outJson($data);
        }
        else
        {
            return outMessage(-1, '非法请求');
        }
    }
}