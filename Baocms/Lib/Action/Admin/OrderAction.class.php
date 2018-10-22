<?php
/*
 * 订单管理
 * 作者：liuqiang
 * 日期: 2018/9/29
 */
class OrderAction extends CommonAction 
{
    public function _initialize()
    {
        parent::_initialize();
        $this->order = M('order');
        $this->order_goods = M('order_goods');
        $this->shop = M('shop');
        $this->reply = M('reply');
        $this->comment = M('comment');
    }
    public function index()
    {
        import('ORG.Util.Page'); // 导入分页类
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
        $orderno = $this->_param('orderno');
        if($orderno){
            $map['o.orderno'] = $orderno;
            $this->assign('orderno',$orderno);
        }
        $shop_name = $this->_param('shop_name');
        if($orderno){
            $map['s.shop_name'] = array('like',"%$shop_name%");
            $this->assign('shop_name',$shop_name);
        }
        $status = $this->_param('status');
        if($status){
            $map['o.status'] = $status;
            $this->assign('status',$status);
        }
        $count = M('order')->alias('o')
                ->join('uboss_order_goods g on o.order_id = g.order_id')
                ->join('uboss_shop s on s.shop_id = o.shop_id')
                ->join('uboss_goods gs on gs.goods_id = g.goods_id')
                ->where($map)
                ->count(); 
        $Page = new Page($count, 10); 
        $show = $Page->show(); 
        $data = M('order')->alias('o')
                ->join('uboss_order_goods g on o.order_id = g.order_id')
                ->join('uboss_shop s on s.shop_id = o.shop_id')
                ->join('uboss_goods gs on gs.goods_id = g.goods_id')
                ->field('o.order_id,o.type,o.status,o.total_price,o.create_time,o.orderno,s.shop_name,o.success_time,g.num,g.price,gs.title')

                ->where($map)
                ->order('o.create_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();
    }
    // 查看
    public function info()
    {
        $map['order_id'] = $this->_param('order_id');
        $list = M('order')->alias('o')
                ->join('uboss_users u on o.user_id = u.user_id')
                ->field('u.nickname,u.mobile,o.*,u.level_id')
                ->where($map)
                ->find();
        $order_goods = M('order_goods')->where('order_id = '.$list['order_id'])->find();
        $list['num'] = $order_goods['num'];
        $list['total'] = $order_goods['total_price'];

        $goods = M('goods')->field('title')->find($order_goods['goods_id']);
        $list['title'] = $goods['title'];
        $this->assign('shopname',$this->member['shop_name']);
        $this->assign('list',$list);
        $this->display();
    }
    // 订单详情
    public function order_detail()
    {
        $oid = intval($_GET['order_id']);
        $goods = M('order_goods')->alias('og')
                ->join('join uboss_goods g on g.goods_id = og.goods_id')
                ->field('g.title,og.num,og.price,og.total_price')
                ->where('og.order_id = '.$oid.' and g.type = 0')->select();
        $this->assign('goods',$goods);
        $this->display();
    }

    public function ajax_agree()
    {
        $id = intval($_POST['id']);
        $data['status'] = 2;
        echo $r = M('back_goods')->where('back_id = '.$id)->save($data);
    }
    public function ajax_refuse()
    {
        $id = intval($_POST['id']);
        $data['status'] = 0;
        echo $r = M('back_goods')->where('back_id = '.$id)->save($data);
    }
    // 评价管理
    public function comment()
    {
        $status = $this->_param('status');
        if($status){
            switch ($status) {
                case 1:
                    $map['reply_status'] = 1;
                    break;
                case 2:
                    $map['reply_status'] = 0;
                    break;
                default:
                    # code...
                    break;
            }
            $this->assign('status',$status);
        }
        // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $map['s.city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $map['s.city_id'] = $this->city_id;
            $map['s.area_id'] = $this->area_id;
        }
        $data = M('comment')->alias('c')
                ->join('uboss_order o on c.order_id = o.order_id')
                ->join('uboss_goods g on g.goods_id = c.goods_id')
                ->join('uboss_users u on u.user_id = c.user_id')
                ->join('uboss_shop s on s.shop_id = g.shop_id')
                ->where($map)
                ->field('c.*,u.nickname,g.title')
                ->select();
        $this->assign('data',$data);
        $this->display();
    }
    // 查看评论详情
    public function com_detail()
    {
        $com_id = $this->_param('com_id');
        $data = $this->comment->alias('c')
                ->join('uboss_users u on u.user_id = c.user_id')
                ->join('uboss_goods g on g.goods_id = c.goods_id')
                ->join('uboss_reply r on r.comment_id = c.comment_id')
                ->where('c.comment_id = '.$com_id)
                ->field('u.mobile,u.nickname,c.*,g.title,r.con')
                ->find();
        $data['comment_img'] = json_decode($data['comment_img']);
        $this->assign('data',$data);
        $this->display();
    }
    // 删除评论
    public function del_com()
    {
        $arr['comment_id'] = $this->_param('com_id');
        $arr['is_del'] = 1;
        $arr['status'] = 0;
        M('comment')->save($arr);
        $this->baoSuccess('删除评论',U('comment'));
    }
}
