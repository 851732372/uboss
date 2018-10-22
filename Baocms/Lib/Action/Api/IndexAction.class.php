<?php
use BaseAction;

/**
 * 首页数据
 * Class IndexAction
 */
class IndexAction extends BaseAction
{
    /**
     * 首页banner/轮播图
     * @author Ginger
     * return
     */
    public function images()
    {
        if ($this->isPost())
        {
            $params = $this->_post();
            $where['city_id'] = session('cityId');
            if ($params['list'] == 'slide') //  轮播图
            {
                $where['type'] = 1;
            }
            elseif ($params['list'] == 'banner')  //  广告图
            {
                $where['type'] = 2;

            }
            else
            {
                return outMessage(-1, '非法请求，参数错误');
            }
            $data = D('SetImg')
                ->field('href skipAddress,img')
                ->where($where)
                ->order('pos ASC')
                ->select();
            return outJson($data);
        }
        else
        {
            return outMessage(-1, '非法请求');
        }
    }
    /**
     * 首页icon/分类列表
     * @author Ginger
     * return
     */
    public function icon()
    {
        if ($this->isPost())
        {
            $params = $this->_post();
            $parentId = (int) $params['higherCategoryId'];
            if (isset($params['higherCategoryId']))
            {
                $obj = D('Shopcate');
                $data = $obj->field('cate_id categoryId,icon img,cate_name title')
                    ->where("parent_id = {$parentId}")
                    ->order("orderby ASC")
                    ->page($this->page, $this->pageSize)
                    ->select();
                return outJson($data, array('current' => $this->page, 'pageSize' => $this->pageSize));
            }
            elseif ($params['list'] == 'all')
            {
                return outJson(D('Shopcate')->getCate());
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
     * 搜索商品
     * @author Ginger
     * return
     */
    public function search()
    {
        $lng = session('location.lng');
        $lat = session('location.lat');
        if (!$this->isPost())   return outMessage(-1, '非法请求');
        $keyWords = $this->_post('keywords') ? htmlspecialchars($this->_post('keywords')) : '' ;
        if (!$keyWords)
        {
            return outMessage(-1, '请输入您要搜索的商家名称');
        }
//        if (isset($params['shopId']) && (int)$params['shopId'] != 0)
//        {
//            $where['g.shop_id'] = $params['shopId'];
//            $where['g.title'] = array('LIKE', '%' . $keyWords . '%');
//            $where['g.closed'] = 0;
//            $where['g.audit'] = 1;
//            $goodsModel = D('Goods g');
//        $list = $goodsModel
//            ->field('g.goods_id goodsId,g.shop_id shopId,g.title productName,g.`mall_price`/100 price,g.photo img,s.shop_name shopName,s.lat,s.lng,b.business_name position,s.cate_id,s.proportions')
//            ->join('uboss_shop s ON s.shop_id = g.shop_id')
//            ->join('uboss_business b ON b.business_id = g.business_id')
//            ->where($where)
//            ->order('g.views desc')
//            ->page($this->page, $this->pageSize)
//            ->select();

        $where['city_id'] = session('cityId');
        $where['founder_id'] = 0;
        $where['closed'] = 0;
        $where['shop_name'] = array('LIKE', '%' . $keyWords . '%');
        $shopModel = D('Shop');
        $list = $shopModel
            ->field("shop_id id,shop_name name,addr address,lat,lng,price/100 price,cate_id categoryId,photo picture,CONCAT(FORMAT((2 * 6378.137* ASIN(SQRT(POW(SIN(3.1415926535898*({$lat}-lat)/360),2)+COS(3.1415926535898*{$lat}/180)* COS(lat * 3.1415926535898/180)*POW(SIN(3.1415926535898*({$lng}-lng)/360),2)))),2)) as distance,proportions")
            ->where($where)
//            ->order('distance ASC')
            ->page($this->page, $this->pageSize)
            ->select();
        foreach ($list as $k => $v)
        {
            $cateId = D('Shopcate')->getParentsId($v['categoryId']);
            $list[$k]['categoryId'] = ($cateId == 0) ? $v['categoryId'] : $cateId;
        }
        array_multisort(array_map(create_function('$n', 'return $n["distance"];'), $list),SORT_ASC, $list );
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));

//        }
//        else
//        {
//            return outMessage(-1, '非法请求，参数错误');
//        }
    }


    /**
     * 猜你喜欢商品接口
     * @author: Ginger
     * @param:
     * @return: json
     */
//    public function like()
//    {
//        $param = $this->_post();
//        $page = $param['current'] ? (int)$param['current'] : $this->page ;
//        $pageSize = $param['pageSize'] ? (int)$param['pageSize'] : $this->pageSize ;
//
//        $where['s.city_id'] = session('cityId');
//        $where['g.closed'] = 0;
//        $where['g.audit'] = 1;
//        $goodsModel = D('Goods g');
//        $list = $goodsModel
//            ->field('g.goods_id goodsId,g.shop_id shopId,g.title productName,g.`mall_price`/100 price,g.photo img,s.shop_name shopName,s.lat,s.lng,b.business_name position,s.cate_id,s.proportions,g.instructions label,g.standard')
//            ->join('uboss_shop s ON s.shop_id = g.shop_id')
//            ->join('uboss_business b ON b.business_id = g.business_id')
//            ->where($where)
//            ->order('g.views desc')
//            ->page($page, $pageSize)
//            ->select();
//        $shopCateModel = D('Shopcate');
//        foreach ($list as $key => $val)
//        {
//            $list[$key]['label'] = explode('|', $val['label']);
//            $list[$key]['distance'] = getDistance($val['lat'], $val['lng'], $this->lat, $this->lng);
//            $parentId = $shopCateModel->getParentsId($val['cate_id']);
//            $list[$key]['categoryId'] = $parentId == 0 ? $val['cate_id'] : $parentId ;
//            $list[$key]['informations'] = $this->_option(unserialize($val['standard']));
//            unset($list[$key]['lat'],$list[$key]['lng'],$list[$key]['cate_id'],$list[$key]['standard']);
//        }
//        $params['current'] = $page;
//        $params['pageSize'] = $pageSize;
//        return outJson($list, $params);
//    }

    /**
     * 猜你喜欢接口
     * @author: Ginger
     * @param:
     * @return: json
     */
    public function like()
    {
        $lng = session('location.lng');
        $lat = session('location.lat');
//        $page = $param['current'] ? (int)$param['current'] : $this->page ;
//        $pageSize = $param['pageSize'] ? (int)$param['pageSize'] : $this->pageSize ;

        // 商品
//        $where['s.city_id'] = session('cityId');
//        $where['g.closed'] = 0;
//        $where['g.audit'] = 1;
//        $goodsModel = D('Goods g');
//        $list = $goodsModel
//            ->field('g.goods_id goodsId,g.shop_id shopId,g.title productName,g.`mall_price`/100 price,g.photo img,s.shop_name shopName,s.lat,s.lng,b.business_name position,s.cate_id,s.proportions,g.instructions label,g.standard')
//            ->join('uboss_shop s ON s.shop_id = g.shop_id')
//            ->join('uboss_business b ON b.business_id = g.business_id')
//            ->where($where)
//            ->order('g.views desc')
//            ->page($page, $pageSize)
//            ->select();
//        $shopCateModel = D('Shopcate');
//        foreach ($list as $key => $val)
//        {
//            $list[$key]['label'] = explode('|', $val['label']);
//            $list[$key]['distance'] = getDistance($val['lat'], $val['lng'], $this->lat, $this->lng);
//            $parentId = $shopCateModel->getParentsId($val['cate_id']);
//            $list[$key]['categoryId'] = $parentId == 0 ? $val['cate_id'] : $parentId ;
//            $list[$key]['informations'] = $this->_option(unserialize($val['standard']));
//            unset($list[$key]['lat'],$list[$key]['lng'],$list[$key]['cate_id'],$list[$key]['standard']);
//        }
//        $params['current'] = $page;
//        $params['pageSize'] = $pageSize;
//        return outJson($list, $params);
        //更换成商家
        $where['city_id'] = session('cityId');
        $where['founder_id'] = 0;
        $where['closed'] = 0;
        $shopModel = D('Shop');
        $list = $shopModel
            ->field("shop_id id,shop_name name,addr address,lat,lng,price/100 price,cate_id categoryId,photo picture,CONCAT(FORMAT((2 * 6378.137* ASIN(SQRT(POW(SIN(3.1415926535898*({$lat}-lat)/360),2)+COS(3.1415926535898*{$lat}/180)* COS(lat * 3.1415926535898/180)*POW(SIN(3.1415926535898*({$lng}-lng)/360),2)))),2)) as distance,proportions")
            ->where($where)
            ->order('distance ASC')
            ->page($this->page, $this->pageSize)
            ->select();
        foreach ($list as $k => $v)
        {
            $cateId = D('Shopcate')->getParentsId($v['categoryId']);
            $list[$k]['categoryId'] = ($cateId == 0) ? $v['categoryId'] : $cateId;
        }
        array_multisort(array_map(create_function('$n', 'return $n["distance"];'), $list),SORT_ASC, $list );
        return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
    }

    public function city()
    {
        $list = D('City');
    }

    /**
     * 返回定位参数
     * @author Ginger
     * return
     */
    public function location()
    {
        $url = $this->_param('url');
        $url = str_replace('&amp;', '&', $url);
        require_cache('jssdk.php');
        $jssdk = new JSSDK("wxece7fccd7def1e55","2trc3wdnorzs5jdxf1ebfiepgjhlpml4", $url);
        $signPackage = $jssdk->GetSignPackage();
        return outJson($signPackage);
    }

}