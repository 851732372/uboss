<?php

/* 
 * 软件为合肥生活宝网络公司出品，未经授权许可不得使用！
 * 作者：baocms团队
 * 官网：www.taobao.com
 * 邮件: youge@baocms.com  QQ 800026911
 */

class LoginAction extends Action{
    
    public function index(){
        
        if($this->isPost()){

            $yzm = $this->_post('yzm');
            if(strtolower($yzm) != strtolower(session('verify'))){
                session('verify',null);
                $this->baoError('验证码不正确!',2000,true);
            }
            $account = $this->_post('account');
            if(empty($account)) {
                session('verify',null);
                $this->baoError('请输入用户名!',2000,true);
            }
      
            $password = $this->_post('password');
            if(empty($password)) {
                session('verify',null);
                $this->baoError('请输入登录密码!',2000,true);
            }
            $res = M('Shop')->find(array("where" => array('account' => $account, 'password' => md5($password),'closed' => 0)));

            if(!empty($res)){
                // $_SESSION['sid'] = $res['shop_id'];
                setUid($res['shop_id']);
                actionLog('登录操作','成功');
                $this->baoSuccess('跳转成功',U('index/index'));
            }
            actionLog('登录操作','失败');
            $this->baoError('跳转失败',3000,true);
        }else{
            $this->display();
        }
    }
    
    public function logout(){
        D('Passport')->logout();
        $this->success('退出登录成功！',U('login/index'));
    }

     public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
    }

    private function parseTemplate($template = '') {

        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        // 获取当前主题名称
        // 获取当前主题的模版路径
        $theme = $this->getTemplateTheme();
        
        define('NOW_PATH',BASE_PATH.'/themes/'.$theme.'Shangjia/');
        // 获取当前主题的模版路径
        define('THEME_PATH', BASE_PATH . '/themes/default/Shangjia/');
        define('APP_TMPL_PATH', __ROOT__ . '/themes/default/Shangjia/');
   
        // 分析模板文件规则
        if ('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = strtolower(MODULE_NAME) . $depr . strtolower(ACTION_NAME);
        } elseif (false === strpos($template, '/')) {
            $template = strtolower(MODULE_NAME) . $depr . strtolower($template);
        }
        $file = NOW_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
        if(file_exists($file)) return $file;
        return THEME_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
    }

     private function getTemplateTheme() {
        define('THEME_NAME','default');
        if ($this->theme) { // 指定模板主题
            $theme = $this->theme;
        } else {
            /* 获取模板主题名称 */
            $theme = D('Template')->getDefaultTheme();
            if (C('TMPL_DETECT_THEME')) {// 自动侦测模板主题
                $t = C('VAR_TEMPLATE');
                if (isset($_GET[$t])) {
                    $theme = $_GET[$t];
                } elseif (cookie('think_template')) {
                    $theme = cookie('think_template');
                }
                if (!in_array($theme, explode(',', C('THEME_LIST')))) {
                    $theme = C('DEFAULT_THEME');
                }
                cookie('think_template', $theme, 864000);
            }
        }
        return $theme ? $theme . '/' : '';
    }
     protected function baoMsg($message, $jumpUrl = '', $time = 3000, $callback = '', $parent = true) {
        $parents = $parent ? 'parent.' : '';
        $str = '<script>';
        $str .=$parents . 'bmsg("' . $message . '","' . $jumpUrl . '","' . $time . '","' . $callback . '");';
        $str.='</script>';
        exit($str);
    }

    protected function baoOpen($message, $close = true, $style) {
        $str = '<script>';
        $str .='parent.bopen("' . $message . '","' . $close . '","' . $style . '");';
        $str.='</script>';
        exit($str);
    }

    protected function baoSuccess($message, $jumpUrl = '', $time = 3000, $parent = true) {
        $this->baoMsg($message, $jumpUrl, $time, '', $parent);
    }

    protected function baoErrorJump($message, $jumpUrl = '', $time = 3000) {
        $this->baoMsg($message, $jumpUrl, $time);
    }

    protected function baoError($message, $time = 3000, $yzm = false, $parent = true) {

        $parent = $parent ? 'parent.' : '';
        $str = '<script>';
        if ($yzm) {
            $str .= $parent . 'bmsg("' . $message . '","",' . $time . ',"yzmCode()");';
        } else {
            $str .= $parent . 'bmsg("' . $message . '","",' . $time . ');';
        }
        $str.='</script>';
        exit($str);
    }

    protected function checkFields($data = array(), $fields = array()) {
        foreach ($data as $k => $val) {
            if (!in_array($k, $fields)) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    protected function ipToArea($_ip) {
        return IpToArea($_ip);
    }    
}