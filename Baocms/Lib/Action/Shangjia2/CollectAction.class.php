<?php
/*
 * 收银管理
 * 作者：liuqiang
 * 日期: 2018/9/17
 */
class CollectAction extends CommonAction 
{
	public function _initialize()
    {
        parent::_initialize();
       
    }
	public function index()
	{
		$this->display();
	}
	// 优惠设置
	public function favor()
	{	
		$data = M('favourable')
				->where('shop_id = '.$this->shop_id)
				->select();
        $this->assign('data',$data);
        $this->display();
	}
    public function do_over_order()
    {

        $r = M('favourable')
            ->where('shop_id = '.$this->shop_id.' and type = '.$_POST['type'])
            ->find();
        if($_POST['type'] == 2){
                $_POST['rate'] = $_POST['money'].'/'.$_POST['rate'];
            }
        if(!$r){
        	$_POST['shop_id'] = $this->shop_id;
            $r = M('favourable')->add($_POST);
        }else{
            $r = M('favourable')
                ->where('shop_id = '.$this->shop_id.' and type = '.$_POST['type'])
                ->save($_POST);
        }
        if($r){
            $this->success('添加成功');
        }else{
            $this->error('失败');
        }
    }
}