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
        if (!$this->isPost())   return outMessage(-1, '非法请求');
        $keyWords = $this->_post('keywords') ? htmlspecialchars($this->_post('keywords')) : '' ;
        if (!$keyWords)
        {
            return outMessage(-1, '请输入您要搜索的商品名称');
        }
//        if (isset($params['shopId']) && (int)$params['shopId'] != 0)
//        {
//            $where['g.shop_id'] = $params['shopId'];
            $where['g.title'] = array('LIKE', '%' . $keyWords . '%');
            $where['g.closed'] = 0;
            $where['g.audit'] = 1;
            $goodsModel = D('Goods g');
        $list = $goodsModel
            ->field('g.goods_id goodsId,g.shop_id shopId,g.title productName,g.`mall_price`/100 price,g.photo img,s.shop_name shopName,s.lat,s.lng,b.business_name position,s.cate_id,s.proportions')
            ->join('uboss_shop s ON s.shop_id = g.shop_id')
            ->join('uboss_business b ON b.business_id = g.business_id')
            ->where($where)
            ->order('g.views desc')
            ->page($this->page, $this->pageSize)
            ->select();
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
//        }
//        else
//        {
//            return outMessage(-1, '非法请求，参数错误');
//        }
    }


    /**
     * 猜你喜欢接口
     * @author: Ginger
     * @param:
     * @return: json
     */
    public function like()
    {
        $param = $this->_post();
        $page = $param['current'] ? (int)$param['current'] : $this->page ;
        $pageSize = $param['pageSize'] ? (int)$param['pageSize'] : $this->pageSize ;

        $where['g.closed'] = 0;
        $where['g.audit'] = 1;
        $goodsModel = D('Goods g');
        $list = $goodsModel
            ->field('g.goods_id goodsId,g.shop_id shopId,g.title productName,g.`mall_price`/100 price,g.photo img,s.shop_name shopName,s.lat,s.lng,b.business_name position,s.cate_id,s.proportions,g.instructions label,g.standard')
            ->join('uboss_shop s ON s.shop_id = g.shop_id')
            ->join('uboss_business b ON b.business_id = g.business_id')
            ->where($where)
            ->order('g.views desc')
            ->page($page, $pageSize)
            ->select();
        $shopCateModel = D('Shopcate');
        foreach ($list as $key => $val)
        {
            $list[$key]['label'] = explode('|', $val['label']);
            $list[$key]['distance'] = getDistance($val['lat'], $val['lng'], $this->lat, $this->lng);
            $parentId = $shopCateModel->getParentsId($val['cate_id']);
            $list[$key]['categoryId'] = $parentId == 0 ? $val['cate_id'] : $parentId ;
            $list[$key]['informations'] = $this->_option(unserialize($val['standard']));
            unset($list[$key]['lat'],$list[$key]['lng'],$list[$key]['cate_id'],$list[$key]['standard']);
        }
        $params['current'] = $page;
        $params['pageSize'] = $pageSize;
//        echo $goodsModel->getLastSql();

//        echo '<pre>';
//        print_r($list);
        return outJson($list, $params);
//        echo json_encode($list);
    }

    public function city()
    {
        $list = D('City');
    }

    public function location()
    {
        require_cache(APP_PATH . 'Lib/Payment/weixin/jssdk.php');
        $jssdk = new JSSDK("wxece7fccd7def1e55","2trc3wdnorzs5jdxf1ebfiepgjhlpml4");
        $signPackage = $jssdk->GetSignPackage();
        return outJson($signPackage);
    }
}