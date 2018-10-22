<?php

/* 
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.taobao.com
 * 邮件: youge@baocms.com  QQ 800026911
 */
class RoleAction extends CommonAction{
    private $create_fields = array('role_name');
    private $edit_fields = array('role_name');
    
    public  function index(){
       $Role = D('Role');
       import('ORG.Util.Page');// 导入分页类
       $keyword = $this->_param('keyword','htmlspecialchars');
       $map = array();
       if($keyword){
           $map['role_name'] = array('LIKE', '%'.$keyword.'%');
       }    
         $this->assign('keyword',$keyword);
         
       $count      = $Role->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Role->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
     
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
    public function auth($role_id = 0){

        if(($role_id = (int)$role_id )&& $detail = D('role')->find($role_id)){
            if ($this->isPost()) {
               $menu_ids = $this->_post('menu_id');
               $obj = D('RoleMaps');
               $obj->delete(array('where'=>" role_id = '{$role_id}' "));
               foreach($menu_ids as $val){
                   if(!empty($val)){
                        $data = array(
                            'role_id'=>$role_id,
                            'menu_id'=> (int)$val,
                        );
                        $obj->add($data);
                   }
               }
               $this->baoSuccess('授权成功！',U('role/auth',array('role_id'=>$role_id)));
            }else{
               $this->assign('menus',D('Menu')->where('is_del = 1')->select());
               $this->assign('menuIds',D('RoleMaps')->getMenuIdsByRoleId($role_id));
               $this->assign('role_id',$role_id);
   
               $this->assign('detail',$detail);
               $this->display();
            }          
        }else{
            $this->error('请选择正确的角色');
        }
    }




    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Role');
            if($obj->add($data)){
                $obj->cleanCache();
                $this->baoSuccess('添加成功',U('role/index'));
            }
            $this->baoError('操作失败！');
        } else {
            $this->display();
        }
    }
    public function edit($role_id = 0){
        if($role_id =(int) $role_id){
            $obj = D('Role');
            $role = $obj->fetchAll();
            if(!isset($role[$role_id])){
                $this->baoError('请选择要编辑的角色');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['role_id'] = $role_id;
                if($obj->save($data)){
                    $obj->cleanCache();
                    $this->baoSuccess('操作成功',U('role/index'));
                }
                $this->baoError('操作失败');
                
            }else{
                $this->assign('detail',$role[$role_id]);         
                $this->display();
            }
        }else{
            $this->baoError('请选择要编辑的角色');
        }
    }
    
    
    public function delete($role_id = 0){
         if($role_id = (int)$role_id){
             $obj =D('Role');
             $obj->delete($role_id);
             $obj->cleanCache();
             $this->baoSuccess('删除成功！',U('role/index'));
         }
         $this->baoError('请选择要删除的组');
    }
    
    
    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        if (empty($data['role_name'])) {
            $this->baoError('请输入角色名称');
        }
        $data['role_name'] = htmlspecialchars($data['role_name'], ENT_QUOTES, 'UTF-8');
        return $data;
    }
    
    private function editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        if (empty($data['role_name'])) {
            $this->baoError('请输入角色名称');
        }
        $data['role_name'] = htmlspecialchars($data['role_name'], ENT_QUOTES, 'UTF-8');
        return $data;  
    }
}
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,40)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,223)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,224)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,225)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,226)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,227)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,265)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,266)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,267)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,268)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,269)
    // INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,515)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,637)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,638)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,315)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,317)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,318)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,319)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,320)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,321)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,322)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,324)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,325)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,392)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,547)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,550)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,44)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,45)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,46)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,47)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,48)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,280)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,530)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,531)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,532)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,533)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,592)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,593)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,594)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,595)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,612)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,613)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,614)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,615)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,616)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,617)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,618)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,636)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,538)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,619)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,620)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,621)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,542)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,543)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,544)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,555)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,590)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,591)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,556)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,622)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,623)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,624)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,625)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,626)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,627)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,628)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,633)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,558)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,629)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,634)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,635)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,607)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,608)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,632)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,604)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,606)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,630)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,631)INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,605)


// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,40)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,607)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,608)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,632)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,604)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,606)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,630)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,631)
// INSERT INTO `uboss_role_maps` (`role_id`,`menu_id`) VALUES (5,605)