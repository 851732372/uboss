<?php
/*
 * 订单管理
 * 作者：liuqiang
 * 日期: 2018/9/8
 */
class PageAction extends CommonAction {
	public $map;
	public function _initialize()
    {
        parent::_initialize();
         // 城市管理查询
        if(0 == $this->area_id && 0 != $this->city_id){
            $this->map['city_id'] = $this->city_id;
        }
        if(0 != $this->city_id && 0 != $this->area_id){
            $this->map['city_id'] = $this->city_id;
            $this->map['area_id'] = $this->area_id;
        }
    }
	public function index()
	{
		$city_id = $this->_param('city_id');
		$type = $this->_param('type');
		if($city_id){
			$this->map['city_id'] = $city_id;
			$this->assign('city_id',$city_id);
		}
		if($type){
			$this->map['type'] = $type;
			$this->assign('type',$type);
		}
		import('ORG.Util.Page'); // 导入分页类
        $count = M('set_img')
				->where($this->map)
                ->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		$data = M('set_img')
				->where($this->map)
				->limit($Page->firstRow . ',' . $Page->listRows)
				->select();
		foreach ($data as $key => $value) {
			$data[$key]['name'] = M('city')->where('city_id = '.$value['city_id'])->getField('name');
		}
		// 城市
		$city = M('city')->select();
		$this->assign('city',$city);
		$this->assign('data',$data);
		$this->assign('count', $count);
        $this->assign('page', $show); // 赋值分页输出
		$this->display();
	}
	public function add()
	{
		if(IS_POST){
			$r = M('set_img')->add($_POST);
			if($r){
				$this->baoSuccess('添加成功',U('index'));
			}else{
				$this->baoError('添加失败');
			}
		}else{
			$this->display();
		}
	}
	public function del()
	{
		$id = intval($_GET['id']);
		$r = M('set_img')->delete($id);
		if($r){
			$this->success('删除成功',U('index'));
		}else{
			$this->success('删除失败');
		}
	}
	public function edit()
	{
		if(IS_POST){
			$r = M('set_img')->save($_POST);
			
			if($r){
				$this->baoSuccess('修改成功',U('index'));
			}else{
				$this->baoError('修改失败');
			}
		}else{
			$id = $_GET['id'];
			$data = M('set_img')->find($id);
			$this->assign('detail',$data);
			$this->display();
		}
		
	}
}