<?php
/*
 * 会员管理
 * 作者：liuqiang
 * 日期: 2018/9/5
 */
class VipAction extends CommonAction {
    public function _initialize()
    {
        parent::_initialize();
        $this->users = M('users');
        $this->order = M('order');
        $this->user_money_logs = M('user_money_logs');
    }
    public function index()
    {   
        // 筛选
        $nickname = $this->_param('nickname');
        $level_id = $this->_param('level_id');
        $is_reg = $this->_param('is_reg');
        $realname = $this->_param('realname');
        if($nickname){
            $map['nickname'] = array('like',"%$nickname%");
            $this->assign('nickname',$nickname);
        }
        if($level_id){
            $map['level_id'] = $level_id;
            $this->assign('level_id',$level_id);
        }
        if($realname){
            $map['realname'] = array('like',"%$realname%");
            $this->assign('realname',$realname);
        }
        if($is_reg){
            switch ($is_reg) {
                case 1:
                    $map['is_reg'] = -1;
                    break;
                case 2:
                    $map['is_reg'] = 0;
                    break;
                case 3:
                    $map['is_reg'] = 1;
                    break;
                case 4:
                    $map['is_reg'] = 2;
                    break;
                default:
                    # code...
                    break;
            }
            $this->assign('is_reg',$is_reg);
        }
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->users
                ->where($map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $data = $this->users
                ->where($map)                
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('count', $count);
        $this->assign('list', $data); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
    // 查看
    public function info()
    {
        $user_id = $this->_param('user_id');
        $map['user_id'] = $user_id;
        $user = $this->users->find($user_id);
        $this->assign('users',$user);
        // 最后下单时间
        $create_time = $this->order->where($map)->getField('max(create_time)');
        $this->assign('create_time',$create_time);
        // 分红历史
        import('ORG.Util.Page'); // 导入分页类
        $count = $this->user_money_logs
                ->where($map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 1 充值 2 余额支付 22 扫码支付 3 提现 4 U店分红 5 消费分成 6 资产变现 
        $data = $this->user_money_logs
                ->where($map)
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('create_time desc')
                ->select();
        $this->assign('data',$data);
        $this->assign('count', $count);
        $this->assign('page', $show); // 赋值分页输出
        // 近7天消费
        $begin = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
        $end = time();
        $map['_string'] = "(create_time >= $begin and create_time <= $end and status >= 4 and status !=5)";
        $res = M('order')
            ->where($map)
            ->order('create_time desc')
            ->select();
        foreach ($res as $key => $value) {
            $goods = M('order_goods')->alias('o')
                    ->join('uboss_goods g on o.goods_id = g.goods_id')
                    ->field('g.title,o.num,o.total_price')
                    ->where('order_id = '.$value['order_id'])
                    ->select();
            foreach ($goods as $key1 => $value1) {
                $res[$key]['goodsname'] = $value1['title'];
                $res[$key]['num'] = $value1['num'];
                $res[$key]['total'] = $value1['total_price'];
            }
        }
        $this->assign('list',$res);
        $this->display();
    }
    
    // public function ajax_select_d(){
    //     $begin = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
    //     $end = time();
    //     $map['user_id'] = $this->_param('user_id');
    //     $map['_string'] = "(create_time >= $begin and create_time <= $end and status >= 4 and status !=5)";
    //     $res = M('order')
    //         ->where($map)
    //         ->order('create_time asc')
    //         ->group('FROM_UNIXTIME(create_time,"%m-%d")')
    //         ->field('FROM_UNIXTIME(create_time,"%m-%d") date,count(*) num')
    //         ->select();
    //         // echo M('order')->getLastSql();
    //     echo outJson($res);
    // }
    // 用户状态
    public function closed()
    {
        $data['user_id'] = $this->_param('user_id');
        $data['closed'] = 0;
        $this->users->save($data);
        $this->baoSuccess('激活成功',U('index'));
    }
    public function unclosed()
    {
        $data['user_id'] = $this->_param('user_id');
        $data['closed'] = 1;
        $this->users->save($data);
        $this->baoSuccess('禁用成功',U('index'));
    }
    // 审核
    public function is_reg()
    {
        $data['user_id'] = $this->_param('user_id');
        $data['is_reg'] = 1;
        $this->users->save($data);
        $this->baoSuccess('认证通过',U('vip/info',array('user_id' => $data['user_id'])));
    }
    public function is_ureg()
    {
        $data['user_id'] = $this->_param('user_id');
        $data['is_reg'] = 2;
        $this->users->save($data);
        $this->baoSuccess('认证拒绝',U('vip/info',array('user_id' => $data['user_id'])));
    }
     // 下级
    // public function down_level()
    // {
    //     $id = $this->_param('user_id');
    //     $r = M('users')->find($id);
    //     $level = $r['level_id'];
    //     $users = M('users')->where('pid = '.$id.' and level_id < '.$level)->select(); 
    //     foreach ($users as $key => $value) {
    //         $data = M('level')
    //             ->where('level_id = '.$value['level_id'])
    //             ->field('levelname')
    //             ->find();
    //         $users[$key]['levelname'] = $data['levelname'];
    //         $users_child = M('users')->where('pid = '.$value['user_id'].' and level_id <'.$value['level_id'])->select(); 
    //         foreach ($users_child as $key1 => $value1) {
    //             $data1 = M('level')
    //                 ->where('level_id = '.$value1['level_id'])
    //                 ->field('levelname')
    //                 ->find();
    //             $users_child[$key1]['levelname'] = $data1['levelname'];
    //         }
    //         $users[$key]['child'] = $users_child;
    //     }
    //     $this->assign('users',$users);
    //     $this->display();
    // }
    public function down_level()
    {
        $id = $this->_param('user_id');
        $r = M('users')->find($id);
        $users = M('users')->where('pid = '.$id)->select(); 
        $pid = M('users')->where('user_id = '.$id)->getField('pid');
        $parent = M('users')->where('user_id = '.$pid)->find();
        $this->assign('parent',$parent);
        $this->assign('users',$users);
        $this->display();
    }
    // 图片放大
    public function ajax_select_img()
    {
        $id = $this->_param('id');
        echo $this->users->where('user_id = '.$id)->getField('face');
    }
    // 身份证放大
    public function ajax_select_sfz()
    {
        $id = $this->_param('id');
        $type = $this->_param('type');
        if($type == 1){
            $card = 'idcard_zimgs';
        }
        if($type == 2){
            $card = 'idcard_fimgs';
        }
        echo $this->users->where('user_id = '.$id)->getField($card);
    }
    // 修改级别
    public function change_level()
    {
        $r = $this->users->save($_POST);
        $this->baoSuccess('修改成功',U('info',array('user_id' => $_POST['user_id'])));
    }
}


