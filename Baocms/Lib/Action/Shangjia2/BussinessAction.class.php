<?php
/*
 * 商户认证管理
 * 作者：liuqiang
 * 日期: 2018/9/3
 */
class BussinessAction extends CommonAction {
    public $sql;
    public $info;
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = M('shop_authen');
        $this->shop_id = getUid();
        $info = $this->sql->where('shop_id = '.$this->shop_id)->find();
        $this->info = $info;
        $info['sfz_file'] = explode(',',$info['sfz_imgs'])[0];
        $info['sfz_file1'] = explode(',',$info['sfz_imgs'])[1];
        if($info){
            $this->assign('info',$info);
        }
    }
    public function index()
    {
        if(IS_POST){
            $_POST['create_time'] = time();
            $res = M('shop_authen')->where('shop_id = '.$this->shop_id)->find();
            if($res){
                $r = M('shop_authen')->where('shop_id = '.$this->shop_id)->save($_POST);
            }else{
                $r = M('shop_authen')->add($_POST);
            }
            if($r){
                // 资料修改或者新添加的都未审核
                $arr['audit'] = 0;
                M('Shop')->where('shop_id = '.$this->shop_id)->save($arr);
                $this->baoSuccess('操作成功',U('bussiness/index'));
            }else{
                $this->baoError('操作失败！');
            }
        }else{
            $shop_id = $this->_param('id');
            $data = M('shop_authen')->alias('a')
                    ->join('uboss_shop s on s.shop_id = a.shop_id')
                    ->field('a.*,s.shop_name,s.audit')
                    ->where('s.shop_id = '.$this->shop_id)
                    ->find();
            $data['idcardimgs1'] = array_filter(explode(',',$data['idcardimgs']));
            $data['licenceimgs1'] = array_filter(explode(',',$data['licenceimgs']));
            $data['meatlicenceimgs1'] = array_filter(explode(',',$data['meatlicenceimgs']));
            $data['shop_id'] = $this->shop_id;
            $arr = M('shop')->field('reason')->find($this->shop_id);
            $data['reason'] = $arr['reason'];
            $this->assign('info',$data);
            $this->display();
        }
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
        $res = M('shop_authen')->field($field)->find($goods_id);
        $arr = explode(',',$res[$field]);
        // 过滤
        $arr = array_filter($arr);
        unset($arr[$key]);
        $img = join(',',$arr);
        $data[$field] = $img;
        echo M('shop_authen')->where('id = '.$id)->save($data);
    }
    // 提现账号
    public  function account()
    {
        if(IS_POST){
            $_POST['shop_id'] = $this->member['shop_id'];
          
            $r = D('Usersex')->save($_POST);
            echo D('Usersex')->getLastSql();
            if($r){
                $this->baoSuccess('修改成功',U('account'));
            }else{
                $res = D('Usersex')->add($_POST);
                $this->baoSuccess('修改成功',U('account'));
            }
        }else{
            $this->assign('detail',D('Usersex')->where('shop_id = '.$this->shop_id)->find());
            $this->display();
        }
        
    }

}