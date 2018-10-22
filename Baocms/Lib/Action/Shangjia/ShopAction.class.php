<?php

/*
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.taobao.com
 * 邮件: youge@baocms.com  QQ 800026911
 */

class ShopAction extends CommonAction {

    public function index() {
        $this->display();
    }

    public function logo() {
        if ($this->isPost()) {
            $logo = $this->_post('logo', 'htmlspecialchars');
            if (empty($logo)) {
                $this->baoError('请上传商铺LOGO');
            }
            if (!isImage($logo)) {
                $this->baoError('商铺LOGO格式不正确');
            }
            $data = array('shop_id' => $this->shop_id, 'logo' => $logo);
            if (D('Shop')->save($data)) {
                $this->baoSuccess('上传LOGO成功！', U('shop/logo'));
            }
            $this->baoError('更新LOGO失败');
        } else {
            $logo = D('shop')->where('shop_id = '.$this->shop_id)->getField('logo');
            $this->assign('logo',$logo);
            $this->display();
        }
    }

    public function image() {
        if ($this->isPost()) {
            $photo = $this->_post('photo', 'htmlspecialchars');
            if (empty($photo)) {
                $this->baoError('请上传商铺形象照');
            }
            if (!isImage($photo)) {
                $this->baoError('商铺形象照格式不正确');
            }
            $data = array('shop_id' => $this->shop_id, 'photo' => $photo);
            if (false !== D('Shop')->save($data)) {
                $this->baoSuccess('上传形象照成功！', U('shop/image'));
            }
            $this->baoError('更新形象照失败');
        } else {
            $photo = D('shop')->where('shop_id = '.$this->shop_id)->getField('photo');
            $this->assign('photo',$photo);
            $this->display();
        }
    }

    // 店铺环境图
    public function photo()
    {
        if($this->isPost()){
            $_POST['shop_id'] = $this->shop_id;
            $r = M('shop')->save($_POST);
            if (false !== $r) {
                $this->baoSuccess('操作成功', U('shop/photo'));
            }
            $this->baoError('操作失败');
        }else{
            $detail = M('shop')->find($this->shop_id);

            if(!empty($detail['otherimgs'])){
                $this->assign('imgs', $detail['otherimgs']);
                $otherimgs = array_filter(explode(',',$detail['otherimgs']));
            }
            $this->assign('otherimgs', $otherimgs);
            $this->display();
        }
    }
    // 删除图片
    public function ajax_del()
    {
        $key = intval($_POST['key']);
        $str = M('shop')->field('otherimgs')->find($this->shop_id);
        $arr = explode(',',$str['otherimgs']);
        // 过滤
        $arr = array_filter($arr);
        unset($arr[$key]);
        $img = join(',',$arr);
        $data['otherimgs'] = $img;
        echo M('shop')->where('shop_id = '.$this->shop_id)->save($data);
    }
    // 删除图片
    public function ajax_del1()
    {
        echo M('shop')->where('shop_id = '.$this->shop_id)->setField('photo','');
    }
    // 删除图片
    public function ajax_del2()
    {
        echo M('shop')->where('shop_id = '.$this->shop_id)->setField('logo','');
    }
}
