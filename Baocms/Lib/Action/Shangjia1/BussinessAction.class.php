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
    // 主体认证
    public function index()
    {   
        if(IS_POST){
            $_POST['sfz_imgs'] = $_POST['sfz_file'].','.$_POST['sfz_file1'];
            $_POST['shop_id'] = $this->shop_id;
            $_POST['authen_time'] = time();
            unset($_POST['sfz_file']);
            unset($_POST['sfz_file1']);
            if(!$this->info){
                $sql = "INSERT INTO `uboss_shop_authen` (`realname`, `telphone`, `sfznumber`, `sfz_imgs`, `licence`, `licence_img`, `meatlicence`, `meatlicence_img`, `shop_id`,`authen_time`) VALUES ('{$_POST['realname']}', {$_POST['telphone']}, '{$_POST['sfznumber']}', '{$_POST['sfz_imgs']}', '', '', '', '', {$_POST['shop_id']},'{$_POST['authen_time']}')";
                $r = $this->sql->execute($sql);
            }else{
                unset($_POST['shop_id']);
                $r = $this->sql->where('shop_id ='.$this->shop_id)->save($_POST);
            }
            if($r){
                $this->baoSuccess('操作成功', U('bussiness/cpany'));
            }else{
                $this->baoError('操作失败！');
            }
        }else{
            $this->display();
        }
        
    }
    // 企业认证
    public function cpany()
    {
        if(IS_POST){
            if(!$this->info){
                $this->baoError('请先填写主体信息');
            }
            $data['licence'] = $_POST['licence'];
            $data['licence_img'] = $_POST['licence_img'];
            $r = $this->sql->where('shop_id ='.$this->shop_id)->save($data);
            if($r){
                $this->baoSuccess('操作成功', U('bussiness/meat'));
            }else{
                $this->baoError('操作失败！');
            }
        }else{
            $this->display();
        }
        
      
    }
    // 餐饮认证
    public function meat()
    {
        if(IS_POST){
            if(!$this->info){
                $this->baoError('请先填写主体信息');
            }
            $data['meatlicence'] = $_POST['meatlicence'];
            $data['meatlicence_img'] = $_POST['meatlicence_img'];
            $r = $this->sql->where('shop_id ='.$this->shop_id)->save($data);
            if($r){
                $this->baoSuccess('操作成功,请等待管理员审核');
            }else{
                $this->baoError('操作失败！');
            }
        }else{
            $this->display();
        }
    }

}