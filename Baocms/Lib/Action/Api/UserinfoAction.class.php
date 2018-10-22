<?php

/**
 * 我的
 * Class UserinfoAction
 */
class UserinfoAction extends BaseAction
{
    private $createFields = array('account', 'password','rank_id', 'nickname','face','ext0');
    private $editFields = array('nickname','face','ext0','idcardno','idcard_zimgs','idcard_fimgs','realname','mobile','email','password','pay_password');
    private $type = array(1 => '充值', 2 => '余额支付', 22 => '扫码支付', 3 => '提现', 4 => 'U店分红', 5 => '消费分成', 6 => '资产变现', 7 => '余额退款');

    /**
     * 我的足迹
     * @author Ginger
     * @param
     * return
     */
    public function footprint()
    {
        if (!$this->isPost()) return outMessage(-1, '非法请求');
        $type = $this->_post('type');
        $option = $this->_post('option');
        if (($type == 'shop') || ($type == 'commodity'))
        {
            $this->_footprint($type, $option);
        }
        else
        {
            return outMessage(-1, '非法请求，参数错误');
        }
    }

    /**
     * 处理足迹方法
     * @author Ginger
     * @param $type 商品/商家
     * @param $option 操作类型
     * return
     */
    private function _footprint($type, $option)
    {
        $goodsId = $shopId = (int) $this->_post('typeId');
        $param = $this->_post();
        if ($option == 'list')
        {
            $where['a.user_id'] = session('userInfo.user_id');
            $where['a.closed'] = 0;
            if ($type == 'shop')
            {
                $list = D('ShopFootprint a')
                    ->field('a.footprint_id id,a.shop_id typeId,s.shop_name name,s.logo picture,s.score,s.addr address')
                    ->join('uboss_shop s ON s.shop_id = a.shop_id')
                    ->where($where)
                    ->order('a.create_time desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
            }
            elseif ($type == 'commodity')
            {
                $list = D('GoodsFootprint a')
                    ->field('a.footprint_id id,a.goods_id typeId,s.shop_name shopName,s.logo picture, g.title name,g.mall_price/100 price')
                    ->join('uboss_goods g ON g.goods_id = a.goods_id')
                    ->join('uboss_shop s ON s.shop_id = g.shop_id')
                    ->where($where)
                    ->order('a.create_time desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize, 'type' => $type, 'option' => $option));
        }
        elseif ($option == 'add')
        {
            if ($type == 'shop')
            {
                if ($shopId == 0) return outMessage(-1,'该商家不存在');
                if (!$detail = D('Shop')->field('shop_id,closed')->find($shopId))
                {
                    return outMessage(-1,'没有该商家');
                }
                if ($detail['closed'])
                {
                    return outMessage(-1,'该商家已下架');
                }
                if ($res = D('Shopfootprint')->check($shopId, session('userInfo.user_id')))
                {
                    if ($res['closed'] == 1)
                    {
                        if (D('ShopFootprint')->save(array('footprint_id' => $res['footprint_id'], 'closed' => 0, 'create_time' => NOW_TIME, 'create_ip' => get_client_ip())))
                        {
                            return outMessage(1,'添加足迹成功！');
                        }
                        return outMessage(-1,'失败！');
                    }
                    return outMessage(-1,'您已经添加过该商家了！');
                }
                $data = array(
                    'shop_id' => $shopId,
                    'user_id' => session('userInfo.user_id'),
                    'create_time' => NOW_TIME,
                    'closed' => 0,
                    'create_ip' => get_client_ip()
                );
                if (D('ShopFootprint')->add($data))
                {
                    return outMessage(1,'添加足迹成功！');
                }
                return outMessage(-1,'失败！');
            }
            elseif ($type == 'commodity')
            {
                if ($goodsId == 0) return outMessage(-1,'该商品不存在');
                if (!$detail = D('Goods')->field('goods_id,closed')->find($goodsId))
                {
                    return outMessage(-1,'没有该商品');
                }
                if ($detail['closed'])
                {
                    return outMessage(-1,'该商品已下架');
                }
                if ($res = D('Goodsfootprint')->check($goodsId, session('userInfo.user_id')))
                {
                    if ($res['closed'] == 1)
                    {
                        if (D('GoodsFootprint')->save(array('footprint_id' => $res['footprint_id'], 'closed' => 0, 'create_time' => NOW_TIME, 'create_ip' => get_client_ip())))
                        {
                            return outMessage(1,'足迹添加成功！');
                        }
                        return outMessage(-1,'失败！');
                    }
                    return outMessage(-1,'您已经添加过该商品了！');
                }
                $data = array(
                    'goods_id' => $goodsId,
                    'user_id' => session('userInfo.user_id'),
                    'closed' => 0,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip()
                );
                if (D('GoodsFootprint')->add($data))
                {
                    return outMessage(1,'足迹添加成功！');
                }
                return outMessage(-1,'失败！');
            }
        }
        elseif ($option == 'del')
        {
            if (empty($param['footprintIds'])) return outMessage(-1,'请选择');
            if ($type == 'shop')
            {
                $shopFavoritesModel = D('ShopFootprint');
                if ($favoritesIds = $this->_checkParams($shopFavoritesModel, $param['footprintIds'], 'footprint_id'))
                {
                    return outMessage(1,'删除成功！');
                }
                else
                {
                    return outMessage(-1,'删除失败！');
                }
            }
            elseif ($type == 'commodity')
            {
                $goodsFootprintModel = D('GoodsFootprint');
                if ($this->_checkParams($goodsFootprintModel, $param['footprintIds'], 'footprint_id'))
                {
                    return outMessage(1,'删除成功！');
                }
                else
                {
                    return outMessage(-1,'删除失败！');
                }
            }
        }
        else
        {
            return outMessage(-1, '非法请求，参数错误');
        }
    }
    /**
     * 购买会员
     * @author Ginger
     * @param
     * return
     */
    public function buyMember()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('id');
            $payType = $this->_post('payType');
            if ($payType != 'weixin' && $payType != 'alipay' && $payType != 'balance') return outMessage(-1, '支付方式不存在');
            if ($payType == 'balance') $payType = 'money';
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $level = $this->_post('level');
            $money = $this->_post('money');
            if ($userId == session('userInfo.user_id'))
            {
//                $data['user_id'] = $userId;
//                $data['deal_money'] = $money;
//                $data['create_time'] = NOW_TIME;
//                $data['deal_type'] = 2;
                $levelInfo = $this->usersModel->field('level_id')->find($userId);
                if ($level == 'gold')   //  黄金
                {
                    if ($levelInfo['level_id'] == 2)  return outMessage(-1, '您已经是黄金会员了');
                    if ($money != '199') return outMessage(-1, '支付金额错误');
                }
                elseif ($level == 'diamond')    //  钻石
                {
                    if ($levelInfo['level_id'] == 3)  return outMessage(-1, '您已经是钻石会员了');
                    if ($money != '999') return outMessage(-1, '支付金额错误');
                }
                else
                {
                    return outMessage(-1,'非法请求，参数错误');
                }
                $logs = array(
                    'type' => 'buyMember',
                    'user_id' => session('userInfo.user_id'),
                    'code' => $payType,
                    'need_pay' => $money*100,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip(),
                    'is_paid' => 0
                );
                if ($logs['log_id'] = D('Paymentlogs')->add($logs))
                {
                    echo json_encode(array('code' => 1, 'message' => '请前往支付！（/api/recharge/pay）', 'id' => $logs['log_id']));
                    return;
                }
                return outMessage(-1, '提交失败，请稍后重试');
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
     * 收货地址
     * @author Ginger
     * @param
     * return
     */
    public function address()
    {
        if (!$this->isPost())
        {
            return outMessage(-1,'非法请求');
        }
        $type = $this->_post('type');
        if ($type == 'add') //  新增
        {
            $this->_addAddress();
        }
        elseif ($type == 'del') //  删除
        {
            $this->_delAddress();
        }
        elseif ($type == 'edit')    //  更新
        {
            $this->_editAddress();
        }
        elseif ($type == 'list')    // 列表
        {
            $this->_listAddress();
        }
        else
        {
            return outMessage(-1,'非法请求，参数错误');
        }
    }

    /**
     * 新增收货地址
     * @author Ginger
     * @param
     * return
     */
    private function _addAddress()
    {
        if ($this->isPost())
        {
            $data = $this->_addressCheck();
            if (!is_array($data)) exit();
            $obj = D('Useraddr');
            $data['is_default'] = 0;
            $data['create_time'] = NOW_TIME;
            if ($obj->add($data))
            {
                return outMessage(1,'新增收货地址成功');
            }
            return outMessage(-1,'操作失败！');
        }
    }

    /**
     * 更新收货地址
     * @author Ginger
     * @param
     * return
     */
    private function _editAddress()
    {
        if ($this->isPost())
        {
            $addressId = (int) $this->_post('id');
            $default = $this->_post('default');
            $obj = D('Useraddr');
            if (!$addressId) return outMessage(-1, '被驳回，请重新操作');
            if (isset($default) && (bool) $default)
            {
                $where['user_id'] = session('userInfo.user_id');
                $where['is_default'] = 1;
                $where['addr_id'] = $addressId;
                if ($obj->where($where)->find()) return outMessage(-1, '该地址已经是默认地址了');
                unset($where['addr_id']);
                if ($res = $obj->field('addr_id')->where($where)->find())
                {
                    if ($obj->where("addr_id = {$res['addr_id']}")->setField('is_default', 0))
                    {
                        if ($obj->where("addr_id = {$addressId}")->setField('is_default', 1)) return outMessage(1, '操作成功');
                    }
                    return outMessage(-1, '操作失败');
                }
                else
                {
                    if ($obj->where("addr_id = {$addressId}")->setField('is_default', 1)) return outMessage(1, '操作成功');
                    return outMessage(-1, '操作失败');
                }
            }
            else
            {
                $data = $this->_addressCheck();
                if (!is_array($data)) exit();
                $data['modify_time'] = NOW_TIME;
                $data['addr_id'] = $addressId;
                if ($obj->save($data))
                {
                    return outMessage(1,'更新收货地址成功');
                }
                return outMessage(-1,'操作失败！');
            }
        }
    }

    /**
     * 删除收货地址
     * @author Ginger
     * @param
     * return
     */
    private function _delAddress()
    {
        if ($this->isPost())
        {
            $addressId = (int) $this->_post('id');
            if (!$addressId) return outMessage(-1, '被驳回，请重新操作');
            $obj = D('Useraddr');
            $where['addr_id'] = $addressId;
//            $data['modify_time'] = NOW_TIME;
//            $data['closed'] = 1;
            if ($obj->where($where)->setField('closed', '1'))
            {
                return outMessage(1,'删除收货地址成功');
            }
            return outMessage(-1,'操作失败！');
        }
    }

    /**
     * 收货地址列表
     * @author Ginger
     * @param
     * return
     */
    private function _listAddress()
    {
        if (!$this->isPost()) return outMessage(-1, '非法请求');
        $addressId = (int) $this->_post('id');
        $obj = D('Useraddr');
        $where['user_id'] = session('userInfo.user_id');
        $where['closed'] = 0;
        if (isset($addressId) && $addressId)
        {
            $where['addr_id'] = $addressId;
            $address = $obj->field('addr_id as id,name as consignee,mobile as tel,concat(province,city,area) as region,address as detailedAddress')
                ->where($where)
                ->find();
            if (!$address) return outMessage(-1, '异常请求');
            return outJson($address);
        }
        else
        {
            $data = $obj->field('addr_id as id,name as consignee,mobile as tel,province,city,area,address as detailedAddress,is_default as `default`')
                ->where($where)
                ->order('is_default DESC,create_time DESC,modify_time DESC')
                ->page($this->page, $this->pageSize)
                ->select();
            return outJson($data, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }

    }

    /**
     * 收货地址数据验证
     * @author Ginger
     * @param
     * return
     */
    private function _addressCheck()
    {
        $data = $this->_post();
        $data['name'] = htmlspecialchars($data['consignee']);
        $data['mobile'] = htmlspecialchars($data['tel']);
        $data['province'] = htmlspecialchars($data['province']);
        $data['city'] = htmlspecialchars($data['city']);
        $data['area'] = htmlspecialchars($data['area']);
        $data['address'] = htmlspecialchars($data['detailedAddress']);
        $data = $this->checkFields($data, array('province', 'city', 'area', 'name', 'mobile', 'address'));
        if (empty($data['name']))
        {
            return outMessage(-1,'收货人不能为空');
            exit();
        }

        if (empty($data['mobile']))
        {
            return outMessage(-1,'手机号码不能为空');
            exit();
        }
        if (!isMobile($data['mobile']))
        {
            return outMessage(-1,'手机号码格式不正确');
            exit();
        }
        $data['user_id'] = (int) session('userInfo.user_id');

        if (empty($data['province']))
        {
            return outMessage(-1,'省份不能为空');
            exit();
        }
        if (empty($data['city']))
        {
            return outMessage(-1,'城市不能为空');
            exit();
        }
        if (empty($data['area']))
        {
            return outMessage(-1,'地区不能为空');
            exit();
        }



        if (empty($data['address']))
        {
            return outMessage(-1,'详细地址不能为空');
            exit();
        }
        return $data;
    }
    /**
     * 添加收藏
     * @author Ginger
     * @param
     * return json
     */
    public function addFavorites()
    {
        if ($this->isPost())
        {
            $param = $this->_post();
            $type = $param['type'];
            $shopId = (int)$param['shopId'];
            $goodsId = (int)$param['goodsId'];

            if ($type == 'commodity')   //商品
            {
                if ($goodsId == 0) return outMessage(-1,'该商品不存在');
                if (!$detail = D('Goods')->field('goods_id,closed')->find($goodsId))
                {
                    return outMessage(-1,'没有该商品');
                }
                if ($detail['closed'])
                {
                    return outMessage(-1,'该商品已下架');
                }
                if ($res = D('Goodsfavorites')->check($goodsId, session('userInfo.user_id')))
                {
                    if ($res['closed'] == 1)
                    {
                        if (D('GoodsFavorites')->save(array('favorites_id' => $res['favorites_id'], 'closed' => 0, 'create_time' => NOW_TIME, 'create_ip' => get_client_ip())))
                        {
                            D('Goods')->where("goods_id = {$goodsId}")->setInc('favorite_num');
                            return outMessage(1,'恭喜您收藏成功！');
                        }
                        return outMessage(-1,'收藏失败！');
                    }
                    return outMessage(-1,'您已经收藏过该商品了！');
                }
                $data = array(
                    'goods_id' => $goodsId,
                    'user_id' => session('userInfo.user_id'),
                    'closed' => 0,
                    'create_time' => NOW_TIME,
                    'create_ip' => get_client_ip()
                );
                if (D('GoodsFavorites')->add($data))
                {
                    D('Goods')->where("goods_id = {$goodsId}")->setInc('favorite_num');
                    return outMessage(1,'恭喜您收藏成功！');
                }
                return outMessage(-1,'收藏失败！');
            }
            elseif ($type == 'shop')    //商家
            {
                if ($shopId == 0) return outMessage(-1,'该商家不存在');
                if (!$detail = D('Shop')->field('shop_id,closed')->find($shopId))
                {
                    return outMessage(-1,'没有该商家');
                }
                if ($detail['closed'])
                {
                    return outMessage(-1,'该商家已下架');
                }
                if ($res = D('Shopfavorites')->check($shopId, session('userInfo.user_id')))
                {
                    if ($res['closed'] == 1)
                    {
                        if (D('ShopFavorites')->save(array('favorites_id' => $res['favorites_id'], 'closed' => 0, 'create_time' => NOW_TIME, 'create_ip' => get_client_ip())))
                        {
                            D('Shop')->where("shop_id = {$shopId}")->setInc('fans_num');
                            return outMessage(1,'恭喜您收藏成功！');
                        }
                        return outMessage(-1,'收藏失败！');
                    }
                    return outMessage(-1,'您已经收藏过该商家了！');
                }
                $data = array(
                    'shop_id' => $shopId,
                    'user_id' => session('userInfo.user_id'),
                    'create_time' => NOW_TIME,
                    'closed' => 0,
                    'create_ip' => get_client_ip()
                );
                if (D('ShopFavorites')->add($data))
                {
                    D('Shop')->where("shop_id = {$shopId}")->setInc('fans_num');
                    return outMessage(1,'恭喜您收藏成功！');
                }
                return outMessage(-1,'收藏失败！');
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
     * 我的收藏
     * @author Ginger
     * @param
     * return json
     */
    public function favorites()
    {
        if ($this->isPost())
        {
            $type = $this->_post('type');
            $where['a.user_id'] = session('userInfo.user_id');
            $where['a.closed'] = 0;
            if ($type == 'commodity')   //商品
            {
                $list = D('GoodsFavorites a')
                    ->field('a.favorites_id id,a.goods_id typeId,s.shop_name shopName,s.logo picture, g.title name,g.mall_price/100 price')
                    ->join('uboss_goods g ON g.goods_id = a.goods_id')
                    ->join('uboss_shop s ON s.shop_id = g.shop_id')
                    ->where($where)
                    ->order('a.favorites_id desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
            }
            elseif ($type == 'shop')    //商家
            {
                $favoritesModel = D('ShopFavorites a');
                $list = $favoritesModel
                    ->field('a.favorites_id id,a.shop_id typeId,s.shop_name name,s.logo picture,s.score,s.addr address')
                    ->join('uboss_shop s ON s.shop_id = a.shop_id')
                    ->where($where)
                    ->order('a.favorites_id desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
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
        return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize, 'type' => $type));
    }

    /**
     * 删除收藏
     * @author Ginger
     * @param
     * return
     */
    public function delFavorites()
    {
        if ($this->isPost())
        {
            $param = $this->_post();
            if (empty($param['collectionIds'])) return outMessage(-1,'请选择');
            $type = $param['type'];
            $where['user_id'] = session('userInfo.user_id');

            if ($type == 'commodity')   //商品
            {
                $goodsFavoritesModel = D('Goodsfavorites');
                if ($favoritesIds = $this->_checkParams($goodsFavoritesModel, $param['collectionIds']))
                {
                    $goodsIds = array();
                    $goodsId = $goodsFavoritesModel->field('goods_id')->where(array('favorites_id' => array('IN', $favoritesIds)))->select();
                    foreach ($goodsId as $v) $goodsIds[] = $v['goods_id'];
                    D('Goods')->where(array("goods_id" =>array('IN', join(',', $goodsIds))))->setDec('favorite_num');
                    return outMessage(1,'删除成功！');
                }
                else
                {
                    return outMessage(-1,'删除失败！');
                }
            }
            elseif ($type == 'shop')    //商家
            {
                $shopFavoritesModel = D('Shopfavorites');
                if ($favoritesIds = $this->_checkParams($shopFavoritesModel, $param['collectionIds']))
                {
                    $shopIds = array();
                    $shopId = $shopFavoritesModel->field('shop_id')->where(array('favorites_id' => array('IN', $favoritesIds)))->select();
                    foreach ($shopId as $v) $shopIds[] = $v['shop_id'];
                    D('Shop')->where(array("shop_id" =>array('IN', join(',', $shopIds))))->setDec('fans_num');
                    return outMessage(1,'删除成功！');
                }
                else
                {
                    return outMessage(-1,'删除失败！');
                }
            }
            else
            {
                return outMessage(-1,'非法请求');
            }
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }
    private function _checkParams($model, $ids, $favoritesId = 'favorites_id')
    {
        if (is_array($ids))
        {
            $idArray = $ids;
        }
        else
        {
            $idArray = explode(',', $ids);
        }

        $idParam = array();
        foreach ($idArray as $key => $val)
        {
            if ((int)$val != 0)
            {
                $idParam[] = (int)$val;
            }
        }
        $idParam = array_unique($idParam);
        $favoritesIds = join(',', $idParam);
        $where[$favoritesId] = array('IN', $favoritesIds);
        $where['closed'] = 0;
        $data = $model->field($favoritesId)->where($where)->order("{$favoritesId} ASC")->select();
        if (!$data) return false;
        $id = array();
        foreach ($data as $v) $id[] = $v[$favoritesId];
        if (array_diff($idParam, $id)) return false;
        if ($model->where($where)->save(array('closed' => 1)))
        {
            return $favoritesIds;
        }
        return false;
    }
    /**
     * 获取单条会员信息
     * @author: Ginger
     * @param:
     * return: array
     */
    public function oneUser()
    {
//        $where['closed'] = 0;
        if (!$this->is_weixin()) return outMessage(-1, '请在微信客户端打开');
        $where['openid'] = session('openid');
        $info = $this->usersModel
            ->field('user_id id,nickname user,face portrait,mobile tel,email,is_reg authentication,level_id member')
            ->where($where)
            ->find();
        if (!empty($info))
        {
            return outJson($info);
        }
        else
        {
            return outMessage(-1, '获取信息失败');
        }
    }

    /**
     * 微信登录（新用户注册，老用户登录）
     * @author Ginger
     * return
     */
    public function login()
    {
        if (!$this->is_weixin()) return outMessage(-1, '请在微信客户端打开');
        $inviteId = (int) $_REQUEST["inviteId"];
        if (!empty($inviteId)) cookie('inviteId', $inviteId);
//        return outMessage($_REQUEST["code"], cookie('inviteId'));
        if (empty(session('openid')))
        {
            import("@/Net.Curl");
            $curl = new Curl();
            $code = $_REQUEST["code"];
//            if (empty($code))
//            {
//                $config = array(
//                    'redirect_uri' => 'http://wx.uboss.net.cn',
//                    'appid' => 'wxece7fccd7def1e55',
//                    'response_type' => 'code',
//                    'scope' => 'snsapi_userinfo',
//                    'state' => '1'
//                );
//                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".http_build_query($config)."#wechat_redirect";
//                header("location:{$url}");
//            }
            $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxece7fccd7def1e55&secret=919a2c069b07190863f15ec96a2e4985&code='.$code.'&grant_type=authorization_code';
            $params = json_decode($curl->get($token_url), true);
            session('openid', $params['openid']);
//            $where['openid'] = $params['openid'];
//            return outMessage(session('openid'),'wu openid');
        }
//        return outMessage(session('openid'),'you openid');
        if (isset($params['openid']) && !empty($params['access_token']))
        {
            $infoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token={$params['access_token']}&openid={$params['openid']}&lang=zh_CN";
            $userInfo = json_decode($curl->get($infoUrl), true);
            session('info', $userInfo);
//            $where['openid'] = $userInfo['openid'];
        }

        $openid = session('openid');;
        $where['openid'] = $openid;
        $info = $this->usersModel
//                ->field('user_id id,nickname user,face portrait,mobile tel,email,is_reg authentication,level_id member')
            ->where($where)
            ->find();
        $invite_id = (int) cookie('inviteId');
        $subscribe = $this->subscribe($openid);
        if (empty($info))
        {
            if (!empty($openid))
            {
                $info = array(
                    'openid' => $openid,
                    'face' => session('info.headimgurl'),
                    'nickname' => htmlspecialchars(session('info.nickname')),
                    'reg_time' => NOW_TIME,
                    'reg_ip' => get_client_ip(),
                    'level_id' => 1,
                    'is_reg' => -1,
                    'subscribe' => $subscribe
                );
                if (!empty($invite_id))
                {
                    $info['invite_id'] = $info['pid'] = $invite_id;
                }
                if ($info['user_id'] = $this->usersModel->add($info))
                {
//                    $info = $this->usersModel
////                            ->field('user_id id,nickname user,face portrait,mobile tel,email,is_reg authentication,level_id member')
//                        ->where($where)
//                        ->find();
                }
            }
        }
        else
        {
            if (!empty($invite_id))
            {
                $beginLastSevenDays = mktime(0,0,0,date('m'),date('d')-6,date('y'));
                $order = D('Order')->where(array('user_id' => $info['user_id'], 'status' => array(array('eq',4),array('gt', 5), 'OR')))->count();
                $parent = $this->usersModel->where(array('pid' => $info['user_id'], 'user_id' => $invite_id))->count();
                if (($order == 0) && ($info['reg_time'] > $beginLastSevenDays) && ($info['pid'] != $invite_id) && ($info['user_id'] != $invite_id) && ($parent == 0))
                {
                    $this->usersModel->save(array('user_id' => $info['user_id'], 'invite_id' => $invite_id, 'pid' => $invite_id, 'subscribe' => $subscribe));
                }
            }
            else
            {
                $this->usersModel->save(array('user_id' => $info['user_id'], 'subscribe' => $subscribe));
            }
            if (empty($info['nickname']) || empty($info['face'])) $this->usersModel->save(array('user_id' => $info['user_id'], 'face' => session('info.headimgurl'), 'nickname' => htmlspecialchars(session('info.nickname'))));
        }
//        return outJson($info);
        setUid($info['user_id']);
        session('userInfo',$info);
        $data = array(
            'id' => $info['user_id'],
            'user' => $info['nickname'],
            'portrait' => $info['face'],
            'tel' => $info['mobile'],
            'authentication' => $info['is_reg'],
            'member' => $info['level_id'],
            'email' => $info['email'],
            'payPwd' => $info['pay_password'] ? true : false,
            'subscribe' => $subscribe
        );
        return outJson($data);
    }

    /**
     * 更新用户信息
     * @author Ginger
     * return
     */
    public function modify()
    {
        if ($this->isPost())
        {
            $params = $this->_post();
            $userId = (int) $params['id'];
            $type = $params['type'];
            if ($userId == session('userInfo.user_id'))
            {
                $info = $this->usersModel
                    ->field('user_id,pay_password')
                    ->find($userId);
                if (empty($info))
                {
                    return outMessage(-1,'会员信息不存在');
                }

                $data = array();
                if (($type == 'user') && $params['user']) //  昵称
                {
                    $data['nickname'] = htmlspecialchars($params[$type]);
                    $data['msg'] = '昵称更新成功';
                }
                elseif (($type == 'portrait') && $params['portrait']) //  头像
                {
                    $data['face'] = base64_image_content($params[$type], 'avatar');
                    $data['msg'] = '头像上传成功';
                }
                elseif ($type == 'authentication') //  认证
                {
                    if (isset($params['verificationCode']) && !empty($params['verificationCode']))
                    {
                        $data = $this->_mobile($params['tel'], $params['verificationCode']);
                        if(isset($data['msg']) && !empty($data['msg'])) return outMessage(-1, $data['msg']);
                        $cardNo = htmlspecialchars($params['idCardNo']);
                        $realName = htmlspecialchars($params['name']);
                        $cardImgZ= base64_image_content($params['idCardPhoto_1'], 'card_photo');
                        $cardImgF= base64_image_content($params['idCardPhoto_2'], 'card_photo');
                        if ($cardNo && $realName && $cardImgZ && $cardImgF)
                        {
                            if (!isCardNo($cardNo)) return outMessage(-1, '您的填写的身份证号码不对，请认真核对');
                            $data['idcardno'] = $cardNo;
                            $data['idcard_fimgs'] = $cardImgF;
                            $data['idcard_zimgs'] = $cardImgZ;
                            $data['realname'] = $realName;
                            $data['is_reg'] = 0;
                            $data['msg'] = '实名认证信息提交成功';
                        }
                        else
                        {
                            return outMessage(-1, '您提交的信息不完整');
                        }
                    }
                    else
                    {
                        $info = $this->_sendSms($params['tel']);
                        if (isset($info['status']) && $info['status'] == 1) return outMessage(1, $info['msg']);
                        return outMessage(-1, $info['msg']);
                    }
                }
                elseif (($type == 'tel') && $params['tel']) //  手机号码
                {
                    if (isset($params['verificationCode']) && !empty($params['verificationCode']))
                    {
                        $data = $this->_mobile($params['tel'], $params['verificationCode']);
                        if(isset($data['msg']) && !empty($data['msg'])) return outMessage(-1, $data['msg']);
                        $data['msg'] = '恭喜您通过手机认证';
                    }
                    else
                    {
                        if (!isMobile($params['tel'])) return outMessage(-1, '手机号码格式错误');
                        if ($user = $this->usersModel->field('user_id')->where(array('mobile' => $params['tel']))->find())  return outMessage(-1, '该手机号已经被注册了');
                        $info = $this->_sendSms($params['tel']);
                        if (isset($info['status']) && $info['status'] == 1) return outMessage(1, $info['msg']);
                        return outMessage(-1, $info['msg']);
                    }
                }
                elseif (($type == 'email') && $params['email']) //  邮箱
                {
                    $email = htmlspecialchars($params['email']);
                    if (!isEmail($email)) return outMessage(-1, '邮箱格式错误');
                    $data['email'] = $email;
                }
                elseif ($type == 'password') //  支付密码
                {
                    if (isset($params['verificationCode']) && !empty($params['verificationCode']))
                    {
                        $data = $this->_mobile($params['tel'], $params['verificationCode']);
                        if(isset($data['msg']) && !empty($data['msg'])) return outMessage(-1, $data['msg']);
                        $data['msg'] = '支付密码设置成功';
                        $pwd = htmlspecialchars($params['password']);
//                        $confirmPwd = htmlspecialchars($params['confirmPwd']);
                        if (!$pwd) return outMessage(-1, '不能为空');
                        $data['pay_password'] = $pwd;
                    }
                    else
                    {
                        $info = $this->_sendSms($params['tel']);
                        if (isset($info['status']) && $info['status'] == 1) return outMessage(1, $info['msg']);
                        return outMessage(-1, $info['msg']);
                    }
                }
                elseif ($type == 'forgetPwd')
                {
                    $oldPwd = htmlspecialchars($params['oldPwd']);
                    $newPwd = htmlspecialchars($params['newPwd']);
                    if (empty($oldPwd) || empty($newPwd)) return outMessage(-1, '原支付密码和新密码不能为空');
                    if (session('userInfo.pay_password') != $oldPwd) return outMessage(-1, '原支付密码错误');
                    $data['pay_password'] = $newPwd;
                    $data['msg'] = '支付密码更新成功';
                }
                else
                {
                    return outMessage(-1,'非法请求，参数错误');
                }

                $data['user_id'] = $userId;
                if (empty($data)) return outMessage(-1, '异常操作');
                if (false !== $this->usersModel->save($data))
                {
                    return outMessage(1,$data['msg']);
                }
                return outMessage(-1,'失败');
            }
            else
            {
                return outMessage(-1,'非法请求，用户参数错误');
            }
        }
        else
        {
            return outMessage(-1, '非法请求');
        }
    }

    /**
     * 充值/提现记录列表
     * @author Ginger
     * return
     */
    public function money()
    {
        if ($this->isPost())
        {
            $type = $this->_post('type');
            if ($type == 'recharge')  //  充值
            {
                $this->recharge();
            }
            elseif ($type == 'rechargeRecord')  //  充值记录
            {
                $this->record(1);
            }
            elseif ($type == 'cash')  //  提现
            {
                $this->cash();
            }
            elseif ($type == 'cashRecord')  //  提现记录
            {
                $this->record(3);
            }
            elseif ($type == 'moneyDetail')  //  收支明细
            {
                $this->record(0);
            }
            elseif ($type == 'moneyInfo')  //  余额信息
            {
                $this->moneyInfo();
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
     * 余额充值
     * @author Ginger
     * return
     */
    protected function recharge()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $payType = $this->_post('payType');
            $money = (int) $this->_post('money');
            if ($payType != 'weixin' && $payType != 'alipay') return outMessage(-1, '支付方式不存在');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $logs = array(
                'type' => 'moneyRecharge',
                'user_id' => session('userInfo.user_id'),
                'code' => $payType,
                'need_pay' => $money*100,
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip(),
                'is_paid' => 0
            );

            if ($logs['log_id'] = D('Paymentlogs')->add($logs))
            {
                echo json_encode(array('code' => 1, 'message' => '请前往支付！（/api/recharge/pay）', 'id' => $logs['log_id']));
                return;
            }
            return outMessage(-1, '提交失败，请稍后重试');
        }
    }

    /**
     * 余额体现
     * @author Ginger
     * return
     */
    protected function cash()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $money = $this->_post('money');
            $account = $this->_post('account');
            $cashType = $this->_post('cashType');
            if (!$userId) return outMessage(-1, 'userId不能为空');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            if ($cashType != 'weixin' && $cashType != 'alipay') return outMessage(-1, '提现账户类型错误 ');
            if ($cashType != 'weixin' && !$account) return outMessage(-1, '提现账户不能为空');
            $putForward = D('UserMoneyLogs')->query("SELECT SUM(money) AS putForward FROM uboss_user_money_logs WHERE user_id = {$userId} AND type = 3 AND `status` = 1");
            $cashMoney = $putForward[0]['putForward'];
            $userMoney = session('userInfo.money');
            $money = $money * 100;
            if ($money > ($userMoney - $cashMoney)) return outMessage(-1, '余额不足，不能提现');
            $moneyModel = D('UserMoneyLogs');
           $data = array(
                'user_id' => $userId,
                'money' => $money,
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip(),
                'intro' => '提现',
                'status' => 1,
                'type' => 3,//提现
            );
           $accountData = array(
                'account_type' => $cashType,
                'account' => $account,
                'create_time' => NOW_TIME
            );
           $moneyModel->startTrans();
           if ($accountData['log_id'] = $moneyModel->add($data))
           {
               if (D('UserCashAccount')->add($accountData))
               {
                   $moneyModel->commit();
                   return outMessage(1, '提现申请成功，审核中...');
               }
           }
            $moneyModel->rollback();
            return outMessage(-1, '失败');
        }
    }

    /**
     * 用户余额变动记录
     * @author Ginger
     * @param $type 1 充值 2 余额支付 22 扫码支付 3 提现 4 U店分红 5 消费分成 6 资产变现 7 余额退款
     * return
     */
    protected function record($type)
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            if (!$userId) return outMessage(-1, 'userId不能为空');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $moneyModel = D('UserMoneyLogs');
            $where['user_id'] = $userId;
//            $where['status'] = 2;
            if ($type != 0) $where['type'] = $type;

            $list = $moneyModel->field('log_id id,pay_no flowNumber,create_time time,`money`/100 money,user_id userId,type,status')
                ->where($where)
                ->order('create_time DESC')
                ->page($this->page, $this->pageSize)
                ->select();
            if ($type == 0)
            {
                foreach ($list as $key => $val) $list[$key]['type'] = $this->type[$val['type']];
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
    }

    /**
     * 用户余额信息
     * @author Ginger
     * return
     */
    private function moneyInfo()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            if (!$userId) return outMessage(-1, 'userId不能为空');
            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $usersModel = D('Users');
            $where['user_id'] = $userId;
            $userInfo = $usersModel->field('money,asset')->find($userId);
            $money = D('UserMoneyLogs')->query("SELECT SUM(money) AS putForward FROM uboss_user_money_logs WHERE user_id = {$userId} AND type = 3 AND `status` = 1");
            $data['putForward'] = $money[0]['putForward']/100;
            $data['balance'] = $userInfo['money']/100;
            $data['asset'] = $userInfo['asset']/100;
            $data['rate'] = C('SET_REMIND');
            return outJson($data);
        }
    }

    /**
     * 我的团队
     * @author Ginger
     * return
     */
    public function team()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
//            $this->usersModel->getTeamNum($userId);return;
            if (!$userId) return outMessage(-1, 'userId不能为空');
//            if ($userId != session('userInfo.user_id')) return outMessage(-1, '用户信息不一致');
            $type = $this->_post('type');
            if (($type != 'customer') && ($type != 'operator') && ($type != 'shareholder')) return outMessage(-1,'非法请求，参数错误');
//            消费者(customer)|经营者(operator)|持股股东(shareholder)
            $where['pid'] = $userId;
            if ($type == 'customer') $where['level_id'] = 1;
            if ($type == 'operator') $where['level_id'] = array('gt', 1);
            if ($type == 'shareholder')
            {
                $list = D('Users u')->field('u.user_id,u.nickname name,u.face portrait,a.apply_position member')
                    ->join('uboss_apply a ON a.user_id = u.user_id')
                    ->where(array('u.pid' => $userId, 'a.status'=> 2))
                    ->order('u.reg_time desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
                foreach ($list as $key => $item)
                {
                    $list[$key]['member'] = $this->applyPosition[$item['member']];
                    $list[$key]['inviteNum'] = $this->usersModel->getInviteNum($item['user_id']);
                    $list[$key]['teamNum'] = $this->usersModel->getTeamNum($item['user_id']);
                    unset($list[$key]['user_id']);
                }
            }
            else
            {
                $list = $this->usersModel->field('nickname name,face portrait,level_id member,user_id')
                    ->where($where)
                    ->order('reg_time desc')
                    ->page($this->page, $this->pageSize)
                    ->select();
                foreach ($list as $key => $item)
                {
                    $list[$key]['member'] = $this->level[$item['member']];
                    $list[$key]['inviteNum'] = $this->usersModel->getInviteNum($item['user_id']);
                    $list[$key]['teamNum'] = $this->usersModel->getTeamNum($item['user_id']);
                    unset($list[$key]['user_id']);
                }
            }
            return outJson($list, array('current' => $this->page, 'pageSize' => $this->pageSize));
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }

    /**
     * 申请合作
     * @author Ginger
     * return
     */
    public function partner()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $params = $this->_post();
            if (empty(htmlspecialchars($params['name']))) return outMessage(-1, '姓名不可空');
            $data['sapplyname'] = htmlspecialchars($params['name']);
            $data['sex'] = htmlspecialchars($params['sexSelect']) == 'male' ? 0 : 1;
            $data['city'] = htmlspecialchars($params['province']) . '-' . htmlspecialchars($params['city']) . '-' . htmlspecialchars($params['area']);
            $shopAppleModel = D('ShopApply');
            if (isset($params['verificationCode']) && !empty($params['verificationCode']))
            {
                $mobile = $this->_mobile($params['tel'], $params['verificationCode']);
                $data['sapplytel'] = $mobile['mobile'];
                if(isset($data['msg']) && !empty($data['msg'])) return outMessage(-1, $data['msg']);
                $data['user_id'] = $userId;
                $data['type'] = 2;
                $data['create_ip'] = get_client_ip();
                $data['create_time'] = NOW_TIME;
                $data['msg'] = '您的信息提交成功，我们会尽快联系您，请保持电话畅通';
            }
            else
            {
                if (!isMobile($params['tel'])) return outMessage(-1, '手机号码格式错误');
                if ($user = $shopAppleModel->field('user_id')->where(array('sapplytel' => $params['tel']))->find())  return outMessage(-1, '您已经提交过了，请不要重复提交');
                $info = $this->_sendSms($params['tel']);
                if (isset($info['status']) && $info['status'] == 1) return outMessage(1, $info['msg']);
                return outMessage(-1, $info['msg']);
            }
            if (false !== $shopAppleModel->add($data))
            {
                return outMessage(1,$data['msg']);
            }
            return outMessage(-1,'信息提交失败，请稍后重试');
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }

    /**
     * 申请加入
     * @author Ginger
     * return
     */
    public function joinUboss()
    {
        if ($this->isPost())
        {
            $userId = (int) $this->_post('userId');
            $params = $this->_post();
            if (empty(htmlspecialchars($params['name']))) return outMessage(-1, '姓名不可空');
            $data['sapplyname'] = htmlspecialchars($params['name']);
            $data['sex'] = htmlspecialchars($params['sexSelect']) == 'male' ? 0 : 1;
            $shopAppleModel = D('ShopApply');
            if (isset($params['verificationCode']) && !empty($params['verificationCode']))
            {
                $mobile = $this->_mobile($params['tel'], $params['verificationCode']);
                $data['sapplytel'] = $mobile['mobile'];
                if(isset($data['msg']) && !empty($data['msg'])) return outMessage(-1, $data['msg']);
                $data['user_id'] = $userId;
                $data['type'] = 1;
                $data['cate_id'] = (int) $params['cateId'];
                $data['create_ip'] = get_client_ip();
                $data['create_time'] = NOW_TIME;
                $data['msg'] = '您的信息提交成功，我们会尽快联系您，请保持电话畅通';
            }
            else
            {
                if (!isMobile($params['tel'])) return outMessage(-1, '手机号码格式错误');
                if ($user = $shopAppleModel->field('user_id')->where(array('sapplytel' => $params['tel']))->find())  return outMessage(-1, '您已经提交过了，请不要重复提交');
                $info = $this->_sendSms($params['tel']);
                if (isset($info['status']) && $info['status'] == 1) return outMessage(1, $info['msg']);
                return outMessage(-1, $info['msg']);
            }
            if (false !== $shopAppleModel->add($data))
            {
                return outMessage(1,$data['msg']);
            }
            return outMessage(-1,'信息提交失败，请稍后重试');
        }
        else
        {
            return outMessage(-1,'非法请求');
        }
    }

//    public function location()
//    {
//        $lat = 37.799225;
//        $lng = 112.619287;
//        $url = "http://api.map.baidu.com/geocoder?location={$lat},{$lng}&output=json&key=28bcdd84fae25699606ffad27f8da77b";
//        $content = file_get_contents($url);
//        $result = json_decode($content, true);
//        if ($result['status'] == 'OK')
//        {
//            return outMessage(1, $result['result']['addressComponent']['city']);
//        }
//        return outMessage(-1, '获取位置失败');
//    }

    public function location()
    {
        //太原
        $lat = 37.799225;
        $lng = 112.619287;
        //汾阳
//        $lat = 37.271453;
//        $lng = 111.794555;
        $lat = (float) $this->_post('lat') ? (float) $this->_post('lat') : $lat ;
        $lng = (float) $this->_post('lng') ? (float) $this->_post('lng') : $lng ;
        $location = array('lat' => $lat, 'lng' =>$lng);
        session('location', $location);

        $squares = $this->returnSquarePoint($lng, $lat);
        $cityModel = D('City');
        $where['lat'] = array(array('gt',$squares['right-bottom']['lat']),array('lt',$squares['left-top']['lat']),array('neq',0));
        $where['lng'] = array(array('gt',$squares['left-top']['lng']),array('lt',$squares['right-bottom']['lng']),array('neq',0));
//        $sql = "select city_id cityId from `uboss_city` where lat <> 0 and lat > {$squares['right-bottom']['lat']} and lat < {$squares['left-top']['lat']} and lng > {$squares['left-top']['lng']} and lng < {$squares['right-bottom']['lng']} limit 1";
//        $currentCity = $cityModel->query($sql);
        $currentCity = $cityModel->field('city_id cityId')->where($where)->find();
//        return outMessage($currentCity);
        $city = $cityModel->field('city_id cityId,name cityName')->select();
        $flag = false;
        foreach ($city as $key => $val)
        {
            if ($val['cityId'] == $currentCity['cityId'])
            {
                $flag = true;
                $city[$key]['currentCity'] = true;
                session('cityId', $currentCity['cityId']);
            }
            else
            {
                $city[$key]['currentCity'] = false;
            }
        }
        if (!$flag)
        {
            $city[0]['currentCity'] = true;
            session('cityId', 1);
        }
        return outJson($city);
    }


    /**
     * 距离最近的城市
     * @author Ginger
     * @param $lng
     * @param $lat
     * @param int $distance
     * return
     */
    public function returnSquarePoint($lng, $lat,$distance = 30)
    {

        $dlng =  2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/6371;
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }

    /**
     * 判断是关注公众号
     * @author Ginger
     * @param $openId
     * return
     */
    private function subscribe($openId)
    {
        import("@/Net.Curl");
        $curl = new Curl();
        $access_token = $this -> _getAccessToken();
        $subscribe_msg = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openId;
        $subscribe = json_decode($curl -> get($subscribe_msg));
        return $subscribe -> subscribe;
    }

    /**
     * 获取access_token
     * @author Ginger
     * return
     */
    private function _getAccessToken()
    {
        import("@/Net.Curl");
        $curl = new Curl();
        $where = array('token' => $this -> token);
         $this -> thisWxUser = M('Wxuser') -> where($where) -> find();
         $url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxece7fccd7def1e55&secret=919a2c069b07190863f15ec96a2e4985';
         $json = json_decode($curl->get($url_get));
         if ($json -> errmsg)
         {
             return '获取access_token发生错误：错误代码'.$json -> errcode.',微信返回错误信息：'.$json -> errmsg;
         }
         return $json -> access_token;
    }
}