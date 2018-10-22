<?php
/*
 * 评论管理
 * 作者：liuqiang
 * 日期: 2018/9/16
 */
class CommentAction extends CommonAction {
    public function _initialize()
    {
        parent::_initialize();
        $this->comment = M('comment');
        $this->reply = M('reply');
    }
    // 评论管理
    public function index()
    {   
        $map['c.shop_id'] = $this->shop_id;
        $data = $this->comment->alias('c')
                ->join('uboss_goods g on g.goods_id = c.goods_id')
                ->join('uboss_users u on u.user_id = c.user_id')
                ->where($map)
                ->field('c.*,u.nickname,g.title')
                ->select();
        $this->assign('data',$data);
        $this->display();
    }
    // 未回复评论管理
    public function noreply()
    {   
        $map['c.shop_id'] = $this->shop_id;
        $map['reply_status'] = 0;
        $data = $this->comment->alias('c')
                ->join('uboss_goods g on g.goods_id = c.goods_id')
                ->join('uboss_users u on u.user_id = c.user_id')
                ->where($map)
                ->field('c.*,u.nickname,g.title')
                ->select();
        $this->assign('data',$data);
        $this->display();
    }
    // 详情
    public function com_detail()
    {
        if(IS_POST){
            $_POST['create_time'] = time();
            $_POST['shop_id'] = $this->shop_id;
            $r = $this->reply->add($_POST);
            if($r){
                $this->comment->where('comment_id = '.$_POST['comment_id'])->save(array('reply_status' => 1));
                $this->baoSuccess('回复成功',U('Comment/index'));
            }else{
                $this->baoError('回复失败');
            }
        }else{
            $com_id = intval($_GET['com_id']);
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
       
    }
    // 评论审核
    public function ajax_refuse()
    {
        $data['comment_id'] = $this->_param('id');
        $data['status'] = 0;
        $this->comment->save($data);
    }
    public function ajax_ok()
    {
        $data['comment_id'] = $this->_param('id');
        $data['status'] = 2;
        $this->comment->save($data);
    }
}