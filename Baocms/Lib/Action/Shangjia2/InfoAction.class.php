<?php

/*
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.taobao.com
 * 邮件: youge@baocms.com  QQ 800026911
 */

class InfoAction extends CommonAction {

    public function ranking() {
        if ($this->member['gold'] < 5) {
            $this->baoError('账户金块余额不足！');
        }
        if (D('Users')->updateCount($this->uid, 'gold', -5)) {
            D('Usergoldlogs')->add(array(
                'user_id' => $this->uid,
                'gold' => -5,
                'intro' => '刷新排名',
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip()
            ));
            D('Shop')->save(array('shop_id' => $this->shop_id, 'ranking' => NOW_TIME));
            $this->baoSuccess('刷新排名成功！', U('index/main'));
        }
        $this->baoError('操作失败');
    }

    public function password() {
        if ($this->isPost()) {
            $oldpwd = $this->_post('oldpwd', 'htmlspecialchars');
            if (empty($oldpwd)) {
                $this->baoError('旧密码不能为空！');
            }
            $newpwd = $this->_post('newpwd', 'htmlspecialchars');
            if (empty($newpwd)) {
                $this->baoError('请输入新密码');
            }
            $pwd2 = $this->_post('pwd2', 'htmlspecialchars');
            if (empty($pwd2) || $newpwd != $pwd2) {
                $this->baoError('两次密码输入不一致！');
            }
            if ($this->member['password'] != md5($oldpwd)) {
                $this->baoError('原密码不正确');
            }
            if (flase !== D('Shop')->save(array('shop_id' => $this->shop_id, 'password' => md5($newpwd)))) {
                session('uid', null);
                $this->baoSuccess('更改密码成功！重新登录后生效');
            }
            $this->baoError('修改密码失败！');
        } else {
            $this->display();
        }
    }

    // 更换手机
    public function telphone()
    {
        
        if ($this->isPost()) {
            $oldtel = $this->_post('oldtel', 'htmlspecialchars');
            $tel = $this->_post('newtel', 'htmlspecialchars');
            $password = $this->_post('password', 'htmlspecialchars');
            if (empty($tel)) {
                $this->baoError('请输入手机号');
            }
            if (empty($tel) || $oldtel == $tel) {
                $this->baoError('两次手机号输入一致！');
            }
            if ($this->member['password'] != md5($password)) {
                $this->baoError('原密码不正确');
            }
            if (flase !== D('Shop')->save(array('shop_id' => $this->shop_id, 'tel' =>$tel))) {
                $this->baoSuccess('更改手机号成功！');
            }
            $this->baoError('修改失败！');
        } else {
            $this->assign('tel',$this->shop['tel']);
            $this->display();
        }
    }

}
