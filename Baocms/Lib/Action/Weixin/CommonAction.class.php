<?php
class  CommonAction extends  Action{
    protected  $_CONFIG = array();
    protected  $_token  = 'ZfDzAv3EZivtQz33WdJ716bj6P8Acuqp'; //默认的TOKEN
    protected  $shop_id = 0;
    protected  $shopdetails = array();
    protected  $weixin = null;
    protected  function _initialize(){ //SHOP_ID 为空的时候        
        $this->_CONFIG = D('Setting')->fetchAll();               
        define('__HOST__', 'http://'.$_SERVER['HTTP_HOST']);
        $this->shop_id = empty($_GET['shop_id']) ? 0 : (int)$_GET['shop_id'];
        if(!empty($this->shop_id)){
            $this->shopdetails = D('Shopdetails')->find($this->shop_id);
        }
        $this->_token = $this->_get_token();
        $this->weixin = D('Weixin');
        $this->weixin->init($this->_token); // 修改了 ThinkWechat  让他支持  主动发送微信消息
       
    }           
    
 


    protected function _get_token(){     
        if(!empty($this->shop_id)){
            return $this->shopdetails['token'];
        }
        return $this->_CONFIG['weixin']['token']; 
    }
   
    
}