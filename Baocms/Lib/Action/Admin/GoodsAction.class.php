<?php
/*
 * 商品管理
 * 作者：liuqiang
 * 日期: 2018/10/11
 */
class GoodsAction extends CommonAction {
    public $map;
    public function _initialize()
    {
        parent::_initialize();
        $this->shop = D('shop');
        $this->shopcate = M('shop_cate c');
         // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $this->map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $this->map['s.city_id'] = $this->city_id;
            $this->map['s.area_id'] = $this->area_id;
        }
    }
    public function index() 
    {
        import('ORG.Util.Page'); // 导入分页类
        // 商家搜索
        $shop_id = $this->_param('shop_id');
        if($shop_id){
            $this->map['g.shop_id'] = $shop_id;
            $this->assign('shop_id',$shop_id);
        }
        // 商家名称
        $shop_name = $this->_param('shop_name');
        if($shop_name){
            $this->map['s.shop_name'] = array('like',"%$shop_name%");
            $this->assign('shop_name',$shop_name);
        }
        // 分类搜索
        // $cate_id = intval($_POST['cate_id']);
        // if($cate_id){
        //     $this->map['g.cate_id'] = $cate_id;
        //     $this->assign('cateid',$cate_id);
        // }
        // 选择状态
        $is_shelf = $this->_param('is_shelf');
        if($is_shelf){
            switch ($is_shelf) {
                case 1:
                    $this->map['g.is_shelf'] = 1;
                    break;
                case 2:
                   $this->map['g.is_shelf'] = 0;
                    break;
                default:
                    # code...
                    break;
            }
            
            $this->assign('is_shelf',$is_shelf);
        }
        // 商品名称
        $title = $this->_param('title');
        if($title){
            $this->map['g.title'] = array('like',"%{$title}%");
            $this->assign('title',$title);
        }
        // 状态
        $closed = $this->_param('closed');
        if($closed){
            switch ($closed) {
                case 1:
                     $this->map['g.closed'] = 1;
                    break;
                case 2:
                     $this->map['g.closed'] = 0;
                    break;
                default:
                    # code...
                    break;
            }
            $this->assign('closed',$closed);
        }
        $this->map['g.is_mall'] = 1;
        $Goods = D('Goods');
        $count = $Goods->alias('g')
                ->join('uboss_shop s on s.shop_id = g.shop_id')
                ->join('uboss_goods_cate c on c.cate_id = g.cate_id')
                ->where($this->map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Goods->alias('g')
                ->join('uboss_shop s on s.shop_id = g.shop_id')
                ->join('uboss_goods_cate c on c.cate_id = g.cate_id')
                ->where($this->map)
                ->field('s.shop_name,g.*,c.cate_name')
                ->order(array('goods_id' => 'desc'))
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
         // 分类
        $this->assign('cates', D('Goodscate')->fetchAll());
         // 商家
        $shop = M('shop')->alias('s')
                ->where($where)
                ->field('shop_id,shop_name')
                ->select();
        $this->assign('count', $count);
        $this->assign('shop',$shop);
        $this->display(); // 输出模板
    }
    public function create() 
    {
        if ($this->isPost()) {
            $_POST['details'] = serialize($_POST['details']);
            $_POST['standard'] = serialize($_POST['standard']);
            // 时间
            $_POST['create_time'] = time();
            // 价格
            $_POST['mall_price'] = $_POST['mall_price']*100;
            // 门店价格
            $_POST['price'] = $_POST['price']*100;
            $sql = "INSERT INTO `uboss_goods` (`title`,`instructions`,`photo`,`photos`,`mall_price`,`price`,`stock`,`views`,`sold_num`,`standard`,`details`,`extends_con`,`create_time`,`shop_id`) VALUES ('{$_POST['title']}','{$_POST['instructions']}','{$_POST['photo']}','{$_POST['photos']}','{$_POST['mall_price']}','{$_POST['price']}','{$_POST['stock']}','{$_POST['views']}','{$_POST['sold_num']}','{$_POST['standard']}','{$_POST['details']}','{$_POST['extends_con']}',{$_POST['create_time']},{$_POST['shop_id']})";
            $r = M('goods')->execute($sql);
            if ($r) {
                $this->baoSuccess('添加成功', U('goods/index'));
            }else{
                $this->baoError('操作失败！');
            }
        } else {
            // 商家
            $shop = M('shop')->alias('s')
                ->where($where)
                ->field('shop_id,shop_name')
                ->select();
            $this->assign('shop',$shop);
            $this->assign('cates', D('Goodscate')->fetchAll());
            $this->display();
        }
    }
    public function edit()
    {
        if($this->isPost()){
            $_POST['mall_price'] = $_POST['mall_price']*100;
            // 门店价格
            $_POST['price'] = $_POST['price']*100;
            if(empty($_POST['standard'][1][1])){
                unset($_POST['standard']);
                $standard = '';
            }else{
                $_POST['standard'] = serialize($_POST['standard']);
                $standard = "`standard`= '{$_POST['standard']}',";
            }
            if(empty($_POST['details'][1][1])){
                unset($_POST['details']);
                $details = '';
            }else{
                $_POST['details'] = serialize($_POST['details']);
                $details = "`details`= '{$_POST['details']}',";
            }
            $sql = "UPDATE `uboss_goods` SET `title`='{$_POST['title']}',`instructions`='{$_POST['instructions']}',`photo`='{$_POST['photo']}',`photos`='{$_POST['photos']}',`mall_price`={$_POST['mall_price']},`price`={$_POST['price']},".$standard."`stock`= {$_POST['stock']},".$details."`views`= {$_POST['views']},`sold_num`= {$_POST['sold_num']} WHERE ( `goods_id` = {$_POST['goods_id']} )";
            $r = M('goods')->execute($sql);
            if (false !== $r) {
                $this->baoSuccess('操作成功', U('goods/index',array('')));
            }
            $this->baoError('操作失败',array(''));
        }else{
            $goods_id = $this->_param('goods_id');
            $detail = M('goods')->find($goods_id);
            if(!empty($detail['photos'])){
                $detail['img'] = array_filter(explode(',',$detail['photos']));
            }
            $detail['shop_name'] = M('shop')->where('shop_id = '.$detail['shop_id'])->getField('shop_name');
            $detail['standard'] = unserialize($detail['standard']);
            $detail['details'] = unserialize($detail['details']);
            $this->assign('detail', $detail);
            $this->assign('cates', D('Goodscate')->fetchAll());
            $this->assign('shop', D('Shop')->select());
            $this->display();
        }
    }
    // 删除图片
    public function ajax_del()
    {
        $goods_id = intval($_POST['id']);
        $key = intval($_POST['key']);
        $str = M('goods')->field('photos')->find($goods_id);
        $arr = explode(',',$str['photos']);
        // 过滤
        $arr = array_filter($arr);
        unset($arr[$key]);
        $img = join(',',$arr);
        $data['photos'] = $img;
        echo M('goods')->where('goods_id = '.$goods_id)->save($data);
    }

    public function delete($goods_id = 0) {
        if (is_numeric($goods_id) && ($goods_id = (int) $goods_id)) {
            $obj = D('Goods');
            $obj->save(array('goods_id' => $goods_id, 'closed' => 1));
            $this->baoSuccess('删除成功！', U('goods/index'));
        } else {
            $goods_id = $this->_post('goods_id', false);
            if (is_array($goods_id)) {
                $obj = D('Goods');
                foreach ($goods_id as $id) {
                    $obj->save(array('goods_id' => $id, 'closed' => 1));
                }
                $this->baoSuccess('删除成功！', U('goods/index'));
            }
            $this->baoError('请选择要删除的商家');
        }
    }
    // 商品审核
    public function audit()
    {
        $data['goods_id'] = $this->_param('goods_id');
        $data['audit'] = 1;
        M('goods')->save($data);
        $this->baoSuccess('通过审核',U('index'));
    }
    public function unaudit()
    {
        $data['goods_id'] = $this->_param('goods_id');
        $data['audit'] = -1;
        M('goods')->save($data);
        $this->baoSuccess('审核拒绝',U('index'));
    }
    // 上下架
    public function shelf()
    {
        $data['goods_id'] = $this->_param('goods_id');
        $data['is_shelf'] = 1;
        $data['audit'] = 1;
        M('goods')->save($data);
        $this->baoSuccess('上架成功',U('index'));
    }
    public function unshelf()
    {
        $data['goods_id'] = $this->_param('goods_id');
        $data['is_shelf'] = 0;
        $data['audit'] = -1;
        M('goods')->save($data);
        $this->baoSuccess('下架成功',U('index'));
    }
    public function select_shop()
    {
         // 搜索商户名称
        $shop_name = $this->_param('shop_name');
        if($shop_name){
            $this->map['s.shop_name'] = array('like',"%{$shop_name}%");
            $this->assign('shop_name',$shop_name);
        }
        // 搜索商户分类
        $cate_id = $this->_param('cate_id');
        if($cate_id){
            $this->map['s.cate_id'] = $cate_id;
            $this->assign('cate_id',$cate_id);
        }
        $this->map['s.founder_id'] = array('eq',0);
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->shop->alias('s')
                ->where($this->map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $this->shop->alias('s')
                ->where($this->map)
                ->order('s.create_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        foreach ($list as $key => $value) {
            // 所属分类
            $list[$key]['cate_name'] = $this->shopcate 
                                        ->where("cate_id = ".$value['cate_id'])
                                        ->getField('cate_name');
        }
         // 分类
         // 城市管理查询
        $this->assign('cates', D('Shopcate')->fetchAll());
        $this->assign('count', $count);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
}
