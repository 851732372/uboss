<?php
/*
 * 商户管理
 * 作者：liuqiang
 * 日期: 2018/9/3
 */
class BussinessAction extends CommonAction {
    public $map;
    public function _initialize()
    {
        parent::_initialize();
        $this->shop = D('shop s');
        $this->order = M('order o');
        $this->shopcate = M('shop_cate c');
        $this->authen = M('shop_authen a');
        $this->user_money_logs = M('user_money_logs l');
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
        $count = $this->shop
                ->where($this->map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $this->shop
                ->where($this->map)
                ->order('s.create_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        foreach ($list as $key => $value) {
            // 营业额
            $order = $this->order
                    ->where('shop_id = '.$value['shop_id'].' and status > 4 and status !=5')
                    ->group('shop_id')
                    ->sum('total_price');
            $list[$key]['sy'] = $order;
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

        // 酒店
        $pid = 12;
        $hotel = M('shop_cate')->where('parent_id = '.$pid)->field('cate_id')->select();
        foreach ($hotel as $key => $value) {
            $hotel_id[] = $value['cate_id'];
        }
        array_push($hotel_id,12);
        $this->assign('hotel_id',$hotel_id);
        $this->display(); // 输出模板
    }
    public function hotel()
    {
        $data['shop_id'] = $this->_param('shop_id');
        $data['is_enough'] = 1;
        M('shop')->save($data);
        $this->baoSuccess('设置成功',U('index'));
    }
    public function hotels()
    {
        $data['shop_id'] = $this->_param('shop_id');
        $data['is_enough'] = 0;
        M('shop')->save($data);
        $this->baoSuccess('设置成功',U('index'));
    }
    // 添加
    public function add()
    {
        if(IS_POST){
            $model = D('Shop');
            $_POST['password'] = md5($_POST['password']);
            $_POST['create_time'] = time();
            $_POST['create_ip'] = get_client_ip();
            $_POST['price'] = $_POST['price']*100;
            $r = $model->add($_POST);
            if($r){
                $this->baoSuccess('添加成功',U('index'));
            }else{
                $this->baoError('添加失败');
            }
        }else{
            // 选择商家
            $user = M('users')->select();
            $this->assign('users',$user);
            $this->assign('cates', D('Shopcate')->fetchAll());
            $this->display();
        }
        
    }
    // 删除商家
    public function del_shop()
    {
        $data['shop_id'] = $this->_param('shop_id');
        $data['closed'] = 1;
        $r = M('shop')->save($data);
        if($r){
            $this->baoSuccess('添加成功',U('index'));
        }else{
            $this->baoError('添加失败');
        }
    }
    //买单设置
    public function over_order()
    {
        $shop_id = $this->_param('id');
        $data = M('favourable')->where('shop_id = '.$shop_id)->select();
        $this->assign('data',$data);
        $this->display();
    }
    public function edit_over()
    {
        if(IS_POST){
            if($this->_param('id')){
                $r = M('favourable')->save($_POST);
            }else{
                $r = M('favourable')->add($_POST);
            }
            if($r){
                $this->baoSuccess('提交成功',U('bussiness/over_order',array('id' => $_POST['shop_id'])));
            }else{
                $this->baoError('提交失败');
            }
        }else{
            $type = $this->_param('type');
            $this->assign('type',$type);
            $id = $this->_param('id');
            if($id){
                $this->assign('id',$id);
                $data = M('favourable')
                        ->where('id = '.$id)
                        ->find();
                $this->assign('dat',$data);
            }
            $shop_id = $this->_param('shop_id');
            $this->assign('shop_id',$shop_id);
            $this->display();
        }
    }
  
    // 分类
    public function classify()
    {
        $Shopcate = D('Shopcate');
        $list = $Shopcate->fetchAll();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
    // 设置基本信息
    public function set()
    {
        if(IS_POST){
            $model = D('Shop');
            $_POST['password'] = md5($_POST['password']);
            $_POST['update_time'] = time();
            $_POST['price'] = $_POST['price']*100;
            $r = $model->save($_POST);
            if($r){
                $this->baoSuccess('提交成功',U('index'));
            }else{
                $this->baoError('提交失败');
            }
        }else{
            $shop_id = intval($_GET['id']);
            $obj = D('Shop');
            $detail = $obj->find($shop_id);
            if(!$detail) {
                $this->error('请选择要编辑的商家');
            }
            $d = M('shop')->find($shop_id);
            if(!empty($d['otherimgs'])){
                $this->assign('imgs', $d['otherimgs']);
                $otherimgs = array_filter(explode(',',$d['otherimgs']));
            }
            $this->assign('otherimgs', $otherimgs); 
            $this->assign('cates', D('Shopcate')->fetchAll());
            $this->assign('detail',$detail);
            $this->display();
        }
        
    }
    // 认证资料
    public function authen()
    {
        import('ORG.Util.Page'); // 导入分页类
         // 搜索商户名称
        $shop_name = $this->_param('shop_name');
        if($shop_name){
            $this->map['s.shop_name'] = array('like',"%{$shop_name}%");
            $this->assign('shop_name',$shop_name);
        }
        // 搜索商户分类
        $audit = $this->_param('audit');
        if(4 != $audit && null != $audit ){
            $this->map['s.audit'] = $audit;
            $this->assign('audit',$audit);
        }
        $count = $this->authen->where($this->map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $data = $this->authen
                ->join('uboss_shop s on s.shop_id = a.shop_id')
                ->field('a.*,s.shop_name,s.audit,s.reason')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->where($this->map)
                ->select();
        $show = $Page->show(); // 分页显示输出
        $this->assign('list',$data);
        $this->assign('count',$count);
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    // 查看认证资料
    public function check_authen()
    {
        if(IS_POST){
            $_POST['create_time'] = time();
            unset($_POST['file']);
            $res = $this->authen->where('shop_id = '.$this->_param('shop_id'))->find();

            if($res){
                $r = $this->authen->save($_POST);

            }else{
                $r = M('shop_authen')->add($_POST);
            }
            if($r){
                // 资料修改或者新添加的都未审核
                $arr['audit'] = 0;
                $this->shop->where('shop_id = '.$this->_param('shop_id'))->save($arr);
                $this->baoSuccess('操作成功',U('bussiness/authen'));
            }else{
                $this->baoError('操作失败！');
            }
        }else{
            $shop_id = $this->_param('id');
            $data = $this->authen
                    ->join('uboss_shop s on s.shop_id = a.shop_id')
                    ->field('a.*,s.shop_name,s.audit')
                    ->where('s.shop_id = '.$shop_id)
                    ->find();
            $data['idcardimgs1'] = array_filter(explode(',',$data['idcardimgs']));
            $data['licenceimgs1'] = array_filter(explode(',',$data['licenceimgs']));
            $data['meatlicenceimgs1'] = array_filter(explode(',',$data['meatlicenceimgs']));
            $this->assign('info',$data);
            $this->assign('is_ok',$this->authen->where('s.shop_id = '.$shop_id)->find());
            $this->display();
        }
    }
        // 删除图片
    public function ajax_del1()
    {
        $key = intval($_POST['key']);
        $id = intval($_POST['id']);
        $str = M('shop')->field('otherimgs')->find($id);
        $arr = explode(',',$str['otherimgs']);
        // 过滤
        $arr = array_filter($arr);
        unset($arr[$key]);
        $img = join(',',$arr);
        $data['otherimgs'] = $img;
        echo M('shop')->where('shop_id = '.$id)->save($data);
    }
    // 删除图片
    public function ajax_del()
    {
        $id = $this->_param('id');
        $key = $this->_param('key');
        $type = $this->_param('type');
        switch ($type) {
            case 1:
                $field = 'idcardimgs';
                break;
            case 2:
                $field = 'licenceimgs';
                break;
            case 3:
                $field = 'meatlicenceimgs';
                break;
            default:
                # code...
                break;
        }
        $res = $this->authen->field($field)->find($goods_id);
        $arr = explode(',',$res[$field]);
        // 过滤
        $arr = array_filter($arr);
        unset($arr[$key]);
        $img = join(',',$arr);
        $data[$field] = $img;
        echo $this->authen->where('id = '.$id)->save($data);
    }
     // 审核认证资料
    public function refuse()
    {
        $str = $_POST['str'];
        parse_str($str,$arr);
        $arr['audit'] = 1;
        $r = M('shop')->save($arr);
        echo $r;
    }
    public function ok()
    {
        $id = intval($_POST['id']);
        $arr['audit'] = 2;
        $r  = M('shop')->where('shop_id = '.$id)->save($arr);
        echo $r;
    }
    // 删除认证
    public function del_authen()
    {
        $shop_id = $_POST['shop_id'];
        $arr['delstatus'] = 1;
        $r = $this->authen->where('shop_id = '.$shop_id)->save($arr);
    }
   
    // 结算管理
    public function commit_money()
    {
         // 搜索商户名称
        $shop_name = $this->_param('shop_name');
        if($shop_name){
            $this->map['s.shop_name'] = array('like',"%{$shop_name}%");
            $this->assign('shop_name',$shop_name);
        }
        $this->map['l.money'] = array('lt',0);
        import('ORG.Util.Page'); 
        $count = $this->user_money_logs
                ->join('uboss_users_ex e on e.shop_id = l.shop_id')
                ->join('uboss_shop s on s.shop_id = l.shop_id')
                ->where($this->map)
                ->count(); 
        $Page = new Page($count, 8); 
        $show = $Page->show(); 
        $data = $this->user_money_logs
                ->join('uboss_users_ex e on e.shop_id = l.shop_id')
                ->join('uboss_shop s on s.shop_id = l.shop_id')
                ->where($this->map)
                ->field('s.shop_name,s.money banlce,l.*,e.*')
                ->order('l.create_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('count', $count);
        $this->assign('data', $data); 
        $this->assign('page', $show); 
        $this->display();
    }
    // 提现审核
    public function ajax_refuse()
    {
        $str = $_POST['str'];
        parse_str($str,$arr);
        $arr['status'] = 0;
        echo $r  = M('user_money_logs')->save($arr);
    }
    public function ajax_ok()
    {
        $id = intval($_POST['id']);
       
        $arr['status'] = 2;
        $r = M('user_money_logs')->where('log_id = '.$id)->save($arr);
        if($r){
            $model = M('shop');
            //开启事务
            $model->startTrans();

            $res = M('user_money_logs')->find($id);
            $data['shop_id'] = $res['shop_id'];
            $shop = $model->find($data['shop_id']);
            // 余额
            $money = $shop['money'];
            // 要扣的本身为负
            $money1 = $res['money'];
            $data['money'] = $money+$money1;
            $r = $model->save($data);
            // 事务 扣钱
            if($r){
                 //执行成功，提交事务
                $model->commit();
            }else{
                //任一执行失败，执行回滚操作，相当于均不执行
                $model->rollback();
            }
        }
       
    }
    // 商家入驻申请
    public function apply()
    {
        import('ORG.Util.Page'); 
        $count = M('shop_apply')
                ->where($map)
                ->count(); 
        $Page = new Page($count, 8); 
        $show = $Page->show(); 
        $data = M('shop_apply')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('count',$count);
        $this->assign('list',$data);
        $this->assign('page', $show);
        $this->display();
    }
     // 商家操作日志
    public function operation_log()
    {
        import('ORG.Util.Page'); 
        $count = M('operation_log')
                ->count(); 
        $Page = new Page($count, 8); 
        $show = $Page->show(); 
        $data = M('operation_log')
                ->order('operation_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('count', $count);
        $this->assign('list', $data); 
        $this->assign('page', $show); 
        $this->display();
    }
    // 商家推荐
    public function recom()
    {
        $data['shop_id'] = $this->_param('shop_id');
        $data['is_recom'] = 1;
        M('shop')->save($data);
        $this->baoSuccess('推荐成功',U('index'));
    }
    public function norecom()
    {
        $data['shop_id'] = $this->_param('shop_id');
        $data['is_recom'] = 0;
        M('shop')->save($data);
        $this->baoSuccess('取消成功',U('index'));
    }
    // 图片放大
    public function ajax_select_simg()
    {
        $id = $this->_param('id');
        echo M('shop')->where('shop_id = '.$id)->getField('logo');
    }
    // 生成短链接
    public function short_url()
    {
        if(IS_POST){
            $shop_id = $this->_param('shop_id');
            $arr['code'] = $this->url_code($shop_id);
            $qrcode_path_new = './attachs/shopewm/code'.'_'. $arr['code'].'.png';
            $arr['qrcode'] = 'shopewm/code'.'_'. $arr['code'].'.png';
            $matrixPointSize = 6;
            $content = $this->_param('content');
            makecode_no_pic($content,$qrcode_path_new,$matrixPointSize,$matrixMarginSize,$errorCorrectionLevel,$qrcode_path_new);
            M('shop')->where('shop_id = '.$shop_id)->save($arr);
            $this->baoSuccess('生成成功',U('Bussiness/index'));
        }else{
            $shop_id = $this->_param('shop_id');
            $code = $this->url_code($shop_id);
            $code = authCode($code,'ENCODE');
            $this->assign('code',$code);
            $this->display();
        }
    }
    public function url_code($url){
        $url= crc32($url);
        $result= sprintf("%u", $url);
        $sUrl= '';
        while($result>0){
            $s= $result%62;
            if($s>35){
                $s= chr($s+61);
            } elseif($s>9 && $s<=35){
                $s= chr($s+ 55);
            }
            $sUrl.= $s;
            $result= floor($result/62);
        }
        return $sUrl;
    }
}
