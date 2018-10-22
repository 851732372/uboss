<?php
class OrderAction extends CommonAction {
    public function index(){
        $Order = D('Order');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('shop_id' => $this->shop_id);
        if (($bg_date = $this->_param('bg_date', 'htmlspecialchars') ) && ($end_date = $this->_param('end_date', 'htmlspecialchars'))) {
            $bg_time = strtotime($bg_date);
            $end_time = strtotime($end_date);
            $map['create_time'] = array(array('ELT', $end_time), array('EGT', $bg_time));
            $this->assign('bg_date', $bg_date);
            $this->assign('end_date', $end_date);
        } else {
            if ($bg_date = $this->_param('bg_date', 'htmlspecialchars')) {
                $bg_time = strtotime($bg_date);
                $this->assign('bg_date', $bg_date);
                $map['create_time'] = array('EGT', $bg_time);
            }
            if ($end_date = $this->_param('end_date', 'htmlspecialchars')) {
                $end_time = strtotime($end_date);
                $this->assign('end_date', $end_date);
                $map['create_time'] = array('ELT', $end_time);
            }
        }
        if(0 != $_POST['status']){
            $map['status'] = intval($_POST['status']);
            $this->assign('status',intval($_POST['status']));
        }
      
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['order_id'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        
        $count = $Order->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Order->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
    // 查看订单商品
    public function shop_details()
    {
        $order_id = intval($_GET['order_id']);
        $data = M('order_goods')->where('order_id = '.$order_id)->select();
        $list = array();
        foreach ($data as $key => $value) {
            $list = M('goods')->where('goods_id = '.$value['goods_id'])->select();
        }
        $this->assign('list',$list);
        $this->display();
    }
    // 发货
    public function send_goods(){
        $id = intval($_POST['id']);
        $arr['status'] = 3;
        $r = M('order')->where('order_id = '.$id)->save($arr);
    }
}
