<?php

/* 
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.baocms.com
 * 邮件: youge@baocms.com  QQ 800026911
 */
class JpushAction extends CommonAction{

    //推送单发
    public function single(){
        if ($this->isPost()) {
            $data = $this->checkFields($this->_post('data', false), array('uid','plat','title','contents', 'url'));
            $data['contents'] = htmlspecialchars($data['contents']);
            if(!empty($data['url'])){
                $data['url'] = htmlspecialchars($data['url']);
            }
            $data['url'] = htmlspecialchars($data['url']);
            $data['type'] = htmlspecialchars($data['plat']);
            $data['sendtime'] = time();
            if($data['type']=='0'){
              $data['type'] = 'android';
            }else if($data['type']=='1'){
              $data['type'] = 'ios';
            }else{
              $data['type'] = 'all';
            }
            $data['photo'] = htmlspecialchars($data['photo']);
            if (!empty($data['photo']) && !isImage($data['photo'])) {
               $this->baoError('缩略图格式不正确');
            }else{
               $data['photo'] = $this->_server('HTTP_HOST').'/attachs/'.$data['photo'];
            }

            import("@/Net.Jpush");
            $PushService = new Jpush();
            //群发信息
            if($id = D('push_history')->add($data)){
                $data['sendno'] = $id;
                $data['sendtype'] = '1';  //发送类型通知
                $data['platform'] = 'android,ios'; //全平台
                $data['contents'] =  json_encode(array('n_builder_id'=>'1', 'n_title'=>$data['title'], 'n_content'=>$data['contents'],'n_extras'=>array('fromer'=>'','fromer_name'=>'','fromer_icon'=>'','image'=>$data['photo'],'sound'=>'')));
                $ret = $PushService->send($data);
                $this->baoSuccess($ret['errcode']);
                if($ret){
                  $this->baoSuccess('发送成功!',U('jpush/single'));
                }else{
                  $this->baoError('发送失败!');
                }
            }else{
                $this->baoError('发送失败!');
            }
          } else {
              $this->display();
          }
    }
    

    //推送群发
    public function mass() {
        if ($this->isPost()) {

            $data = $this->checkFields($this->_post('data', false), array('plat','title','contents', 'url'));
            $data['contents'] = htmlspecialchars($data['contents']);
            if(!empty($data['url'])){
                $data['url'] = htmlspecialchars($data['url']);
            }
            $data['url'] = htmlspecialchars($data['url']);
            $data['type'] = htmlspecialchars($data['plat']);
            $data['sendtime'] = time();
            if($data['type']=='0'){
              $data['type'] = 'android';
            }else if($data['type']=='1'){
              $data['type'] = 'ios';
            }else{
              $data['type'] = 'all';
            }
            $data['photo'] = htmlspecialchars($data['photo']);
            if (!empty($data['photo']) && !isImage($data['photo'])) {
               $this->baoError('缩略图格式不正确');
            }else{
               $data['photo'] = urlencode($this->_server('HTTP_HOST').'/attachs/'.$data['photo']);
            }

            import("@/Net.Jpush");
            $PushService = new Jpush();
            $data['sendtype'] = '1';  //发送类型通知
            //群发信息
            if($id = D('push_history')->add($data)){
                $data['sendno'] = $id;
                $data['receiver_type'] = 4; //发送范围广播
                $data['platform'] = 'android,ios'; //全平台
                $data['contents'] =  json_encode(array('n_builder_id'=>'1', 'n_title'=>$data['title'], 'n_content'=>$data['contents'],'n_extras'=>array('fromer'=>'','fromer_name'=>'','fromer_icon'=>'','image'=>$data['photo'],'sound'=>'')));
                $ret = $PushService->send($data);
                if($ret){
                  $this->baoSuccess('发送成功!',U('jpush/mass'));
                }else{
                  $this->baoError('发送失败!');
                }
            }else{
                $this->baoError('发送失败!');
            }
          } else {
              $this->display();
          }
    }



    public function history(){
       $push_history = D('push_history');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $push_history->where($map)->count();// 查询满足要求的总记录数 
       

       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $push_history->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }

}
