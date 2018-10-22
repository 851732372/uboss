<?php

/*
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.baocms.com
 * 邮件: youge@baocms.com  QQ 800026911
 */

class CheckAction extends CommonAction {

    protected $shop_id = "";
    protected $branch_id = "";

    public function _initialize() {
        parent::_initialize();
        $this->shop_id = cookie('shop_id');
        $this->branch_id = cookie('branch_id');
        $this->pwd = cookie('pwd');
        $this->assign('shop_id', $this->shop_id);
        $this->assign('branch_id', $this->branch_id);
    }

    public function index() {
        $shop_id = (int) $this->_get('shop_id');
        $branch_id = (int) $this->_get('branch_id');
        if (empty($shop_id) || empty($branch_id)) {
            $this->error('参数错误');
        }
        cookie('shop_id', $shop_id, 86400);
        cookie('branch_id', $branch_id, 86400);
        if (empty($this->pwd)) {
            header("Location: " . U('pchome/check/password'));
            die;
        }
        $result = D('Shopbranch')->where(array('shop_id' => $shop_id, 'branch_id' => $branch_id))->find();
        if (empty($result)) {
            $this->error('参数错误');
        }
        if (empty($result['password'])) {
            $this->error('您的店没设置口令，暂不支持！');
        }
        if ($this->isPost()) {
            $code = $this->_post('code', false);
            $res = array();
            foreach ($code as $k => $val) {
                if (!empty($val)) {
                    $res[$k] = $val;
                }
            }
            if (empty($res)) {
                $this->baoMsg('请输入抢购券！');
            }
            $obj = D('Tuancode');
            $shopmoney = D('Shopmoney');
            $return = array();
            $ip = get_client_ip();
            if (count($code) > 10) {
                //echo '<script>parent.alert("一次最多验证10条抢购券!");//</ script>';
                $this->baoMsg("一次最多验证10条抢购券!");
            }
            $userobj = D('Users');
            foreach ($code as $key => $var) {
                $var = trim(htmlspecialchars($var));

                if (!empty($var)) {
                    $data = $obj->find(array('where' => array('code' => $var)));
                    if (!empty($data) && $data['shop_id'] == $this->shop_id && $data['branch_id'] == $branch_id && (int) $data['is_used'] == 0 && (int) $data['status'] == 0) {
                        if ($obj->save(array('code_id' => $data['code_id'], 'is_used' => 1))) { //这次更新保证了更新的结果集              
                            //增加MONEY 的过程 稍后补充
                            if (!empty($data['price'])) {
                                $data['intro'] = '抢购消费' . $data['order_id'];
                                $shopmoney->add(array(
                                    'shop_id' => $data['shop_id'],
                                    'money' => $data['settlement_price'],
                                    'create_ip' => $ip,
                                    'create_time' => NOW_TIME,
                                    'order_id' => $data['order_id'],
                                    'intro' => $data['intro'],
                                ));
                                $return[$var] = $var;
                                $obj->save(array('code_id' =>$data['code_id'],'used_time' => NOW_TIME, 'used_ip' => $ip)); //拆分2次更新是保障并发情况下安全问题
                                echo '<script>parent.used(' . $key . ',"√验证成功",1);</script>';
                            } else {
                                echo '<script>parent.used(' . $key . ',"√到店付抢购券验证成功",2);</script>';
                            }
                            //这样性能有点不好
                            //$order = D('Tuanorder')->find($data['order_id']);
                            //$tuan = D('Tuan')->find($data['tuan_id']);
                            //$integral = (int) ($order['total_price'] / 100);
                            //D('Users')->addIntegral($data['user_id'], $integral, '抢购' . $tuan['title'] . ';订单' . $order['order_id']);
                            //可以优化的 不过最多限制了10条！
                        }
                    } else {
                        //$this->baoError('该抢购券无效');
                        echo '<script>parent.used(' . $key . ',"X该抢购券无效",3);</script>';
                    }
                }
            }
        } else {
            $this->display();
        }
    }

    public function password() {
        if ($this->isPost()) {
            $password = htmlspecialchars($_REQUEST['password']);
            if (empty($password)) {
                $this->error('口令不能为空');
            }
            $result = D('Shopbranch')->where(array('shop_id' => $this->shop_id, 'branch_id' => $this->branch_id))->find();
            if ($password != $result['password']) {
                $this->error('口令不正确');
            }
            cookie('pwd', $password, 86400);
            $this->success('验证通过，您现在可以进行其他操作了', U('check/index', array('shop_id' => $this->shop_id, 'branch_id' => $this->branch_id)));
        } else {
            $this->display();
        }
    }

    public function coupon() {
        $shop_id = (int) $this->_param('shop_id');
        $branch_id = (int) $this->_param('branch_id');
        if (empty($this->pwd)) {
            header("Location: " . U('pchome/check/password'));
            die;
        }
        if ($this->isPost()) {
            $code = $this->_post('code', false);
            $res = array();
            foreach ($code as $k => $val) {
                if (!empty($val)) {
                    $res[$k] = $val;
                }
            }
            if (empty($res)) {
                $this->baoMsg('请输入电子优惠券！');
            }
            $obj = D('Coupondownload');
            $return = array();
            $ip = get_client_ip();
            foreach ($code as $var) {
                if (!empty($var)) {
                    $data = $obj->find(array('where' => array('code' => $var)));
                    if (!empty($data) && $data['shop_id'] == $this->shop_id && $data['is_used'] == 0) {
                        $obj->save(array('download_id' => $data['download_id'], 'is_used' => 1, 'used_time' => NOW_TIME, 'used_ip' => $ip));
                        $return[$var] = $var;
                    }
                }
            }
            if (empty($return)) {
                $this->baoMsg('没有可消费的电子优惠券！');
            }
            if (NOW_TIME - $this->shop['ranking'] < 86400) { //更新排名
                D('Shop')->save(array('shop_id' => $this->shop_id, 'ranking' => NOW_TIME));
            }
            //exit('<script>parent.used("' . join(',', $return) . '");</ script>');
            $message = "恭喜您，您成功消费的优惠券如下：" . join(',', $return);
            $this->baoOpen($message, true, "layui-layer-demo");
        } else {
            $this->display();
        }
    }

    public function tuanlist() {
        $shop_id = (int) $this->_param('shop_id');
        $branch_id = (int) $this->_param('branch_id');
        if (empty($this->pwd)) {
            header("Location: " . U('pchome/check/password'));
            die;
        }
        $tuancode = D('Tuancode');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('status' => 0,'is_used'=>1, 'shop_id' => $shop_id, 'branch_id' => $branch_id, 'fail_date' => array('EGT', TODAY));
        $count = $tuancode->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $tuancode->where($map)->order(array('code_id' => 'asc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $tuan_ids = $user_ids = array();
        foreach ($list as $k => $val) {
            if (!empty($val)) {
                $tuan_ids[$val['tuan_id']] = $val['tuan_id'];
                $user_ids[$val['user_id']] = $val['user_id'];
            }
        }
        $shop = D('Shop')->find($shop_id);
        $branch = D('Shopbranch')->find($branch_id);
        $this->assign('shops',$shop);
        $this->assign('branch',$branch);
        $this->assign('tuans',D('Tuan')->itemsByIds($tuan_ids));
        $this->assign('users',D('Users')->itemsByIds($user_ids));
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }

    public function couponlist() {
        $this->assign();
        $this->display();
    }

}
