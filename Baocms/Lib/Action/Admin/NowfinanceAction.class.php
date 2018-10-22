<?php
/*
 * 提现设置
 * 作者：liuqiang
 * 日期: 2018/10/12
 */
class NowfinanceAction extends CommonAction {
	public function _initialize()
	{
		parent::_initialize();
		$this->cash_set = M('cash_set');
	}
	public function index()
	{
		if(IS_POST){
			$url = BASE_PATH.'/'.APP_NAME.'/Conf/remind.php';
			$str = "<?php
						".'$dbconfigs'." = require  BASE_PATH.'/'.APP_NAME.'/Conf/config.php';\n"
						.'$remind'." =  array(
						    'SET_REMIND' => {$_POST['remind']},
						);
						return array_merge(".'$remind'.",".'$dbconfigs'.");
					?>";
			file_put_contents($url,$str);
			$this->baoSuccess('修改成功',U('index'));
		}else{
			$this->display();
		}
		
	}
}
