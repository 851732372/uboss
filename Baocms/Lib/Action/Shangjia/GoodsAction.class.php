<?php
/*
 * 商品管理
 * 作者：liuqiang
 * 日期: 2018/9/15
 */
class GoodsAction extends CommonAction {

    private $create_fields = array('title', 'photo', 'cate_id', 'price', 'shopcate_id', 'mall_price','price', 'commission','instructions', 'details', 'end_date','stock');
    private $edit_fields = array('title', 'photo', 'cate_id', 'price', 'shopcate_id', 'mall_price', 'price','commission','instructions', 'details', 'end_date','stock','standard');
    public function _initialize() {
        parent::_initialize();
        $this->autocates = D('Goodsshopcate')->where(array('shop_id' => $this->shop_id))->select();
        $this->assign('autocates', $this->autocates);

    }
    // 商品列表
    public function index() {
        $Goods = D('Goods');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed' => 0, 'shop_id' => $this->shop_id, 'is_mall' => 1);
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if ($cate_id = (int) $this->_param('cate_id')) {
            $map['cate_id'] = array('IN', D('Goodscate')->getChildren($cate_id));
            $this->assign('cate_id', $cate_id);
        }

        if ($audit = (int) $this->_param('audit')) {
            $map['audit'] = ($audit === 1 ? 1 : 0);
            $this->assign('audit', $audit);
        }
        $count = $Goods->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Goods->where($map)->order(array('goods_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
    
    // 发布商品
    public function create() {
        if ($this->isPost()) {
            
            $_POST['details'] = serialize($_POST['details']);
            $_POST['standard'] = serialize($_POST['standard']);
            // 时间
            $_POST['create_time'] = time();
            // 价格
            $_POST['mall_price'] = $_POST['mall_price']*100;
            $_POST['price'] = $_POST['price']*100;
            $_POST['shop_id'] = $this->shop_id;
            $sql = "INSERT INTO `uboss_goods` (`title`,`instructions`,`photo`,`photos`,`mall_price`,`price`,`stock`,`views`,`sold_num`,`standard`,`details`,`extends_con`,`create_time`,`shop_id`) VALUES ('{$_POST['title']}','{$_POST['instructions']}','{$_POST['photo']}','{$_POST['photos']}','{$_POST['mall_price']}','{$_POST['price']}','{$_POST['stock']}','{$_POST['views']}','{$_POST['sold_num']}','{$_POST['standard']}','{$_POST['details']}','{$_POST['extends_con']}',{$_POST['create_time']},{$_POST['shop_id']})";
            $r = M('goods')->execute($sql);
            if ($r) {
                $this->baoSuccess('添加成功', U('goods/index'));
            }else{
                $this->baoError('操作失败！');
            }
        } else {
            // $this->assign('cates', D('Goodscate')->fetchAll());
            $this->display();
        }
    }

    public function edit()
    {
        if($this->isPost()){

            $_POST['mall_price'] = $_POST['mall_price']*100;
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
    // 上下架
    public function ajax_shelf()
    {
        $obj = D('goods');
        $data = $_POST;
        $r = $obj->field('audit')->find($data['id']);
        if($r['audit']){
            $res = $obj->where("goods_id = ".$data['id'])->save(array('is_shelf' => $data['status']));
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }

    }
    // 删除
    public function deletegoods()
    {
        $goods_id = $this->_param('goods_id');
        $arr['closed'] = 1;
        $r = M('Goods')->where('goods_id = '.$goods_id)->save($arr);
        $this->baoSuccess('删除成功', U('goods/index'));
    }
}
