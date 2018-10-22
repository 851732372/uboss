<?php
class IndexAction extends CommonAction {

    public function index() {
exit(90);
        $data = $this->weixin->request();
        return outMessage($data);
        switch ($data['MsgType']) {
            case 'event':

                if ($data['Event'] == 'subscribe') {
					$event = $data['Event'];
                    if (isset($data['EventKey']) && !empty($data['EventKey'])) {	// 扫描二维码事件
                        $this->events();
                    } else {
                        $this->event($event,$data);	// 非扫描二维码事件
                    }
                }
                if ($data['Event'] == 'SCAN') {
                    $this->scan();
                }
                break;
            case 'location':

                $this->location($data);
                break;

            default: //其余的类型都算关键词              
                $this->keyword($data);
                break;
        }
    }

    private function location($data) {
        $lat = $data['Location_X'];
        $lng = $data['Location_Y'];

		//腾讯转百度坐标
		if(!empty($lat) && !empty($lng)){	
			$arr = placeToBaidu($lng,$lat);
			$lat = $arr['lat'];
			$lng = $arr['lng'];
		}
		
        $list = D('Shop')->where(array('audit' => 1, 'closed' => 0))->order(" (ABS(lng - '{$lng}') +  ABS(lat - '" . $lat . "') )  asc ")->limit(0, 10)->select();

        if (!empty($list)) {
            $content = array();
            foreach ($list as $item) {
                $content[] = array(
                    $item['shop_name'],
                    $item['addr'],
                    $this->getImage($item['photo']),
                    __HOST__ . '/mobile/shop/detail/shop_id/' . $item['shop_id'] . '.html',
                );
            }
            $this->weixin->response($content, 'news');
        } else {
            $this->weixin->response('很抱歉没有合适的商家推荐给您', 'text');
        }
    }

    private function keyword($data) {
        if (empty($data['Content']))
            return;

        D('Weixinmsg')->add(array(
            'FromUserName' => $data['FromUserName'],
            'ToUserName' => $data['ToUserName'],
            'Content' => htmlspecialchars($data['Content']),
            'create_time' => NOW_TIME
        ));

        if ($this->shop_id == 0) {
			$key = explode(' ',$data['Content']);
            $keyword = D('Weixinkeyword')->checkKeyword($key[0]);
            if ($keyword) {
				$openid = $data['FromUserName'];
				$con = D('Connect')->getConnectByOpenid('weixin',$openid);
				$usr = D('Users')->where(array('user_id' => $con['uid']))->find();
                switch ($keyword['type']) {
                    case 'text':
						//昵称帐号替换
						$text =$keyword['contents'];
						$text = str_replace('|nickname|',$usr['nickname'],$text);
						$text = str_replace('|account|',$usr['account'],$text);
                        $this->weixin->response($text, 'text');
                        break;
                    case 'news':
						//昵称帐号替换
						$text =$keyword['contents'];
						$text = str_replace('|nickname|',$usr['nickname'],$text);
						$text = str_replace('|account|',$usr['account'],$text);
                        $content = array();
                        $content[] = array(
                            $keyword['title'],
                            $text,
                            $this->getImage($keyword['photo']),
                            $keyword['url'],
                        );
                        $this->weixin->response($content, 'news');
                        break;
                    case 'pram':
						$pram = explode('/', $keyword['contents']);
						$text = '暂时没有为您找到内容！';
						import("@/Wei.".$pram[0]);
						$this->wei = new Wei();
						$text = $this->wei->get($key);
						$this->weixin->response($text,'text');
                        break;
                }
            } else {
				
				// 没有特定关键词则查询POIS信息
				$openid = $data['FromUserName'];
				$con = D('Connect')->getConnectByOpenid('weixin',$openid);
				$usr = D('Users')->where(array('user_id' => $con['uid']))->find();
				$map = array();
				$map['name|tag'] = array('LIKE',array('%'.$key[0].'%','%'.$key[0],$key[0].'%','OR'));
				$lat = $usr['lat'];
				$lng = $usr['lng'];
				if (empty($lat) || empty($lng)) {
					$lat = $this->_CONFIG['site']['lat'];
					$lng = $this->_CONFIG['site']['lng'];
				}
				$squares = returnSquarePoint($lng,$lat,2);
				$map['lat'] > $squares['right-bottom']['lat'];
				$map['lat'] < $squares['left-top']['lat'];
				$map['lng'] > $squares['left-top']['lng'];
				$map['lng'] > $squares['right-bottom']['lng'];
				
				$orderby = "orderby asc";
				
				

				//查询包年固顶
				$word = D('Nearword')->where(array('text' => $key[0]))->find();
				$word_pois = $word['pois_id'];
				if($word_pois){
					$ding = D('Near')->find($word_pois);
				}
				
				if($ding){
					$map['pois_id'] <> $word_pois;
					
					if($ding['shop_id']){
						$url = $this->_CONFIG['site']['host'].'/mobile/shop/detail/shop_id/'.$ding['shop_id'].'.html';
					}else{
						$url = $this->_CONFIG['site']['host'].'/mobile/near/detail/pois_id/'.$ding['pois_id'].'.html';
					}
					
					
					$text = "<a href=\"".$url."\">".$ding['name']."</a> ★★★★★ /:strong\n".$ding['address']."\n".$ding['telephone']."\n\n\n";
					
				}
				

				$list = D('Near')->where($map)->order($orderby)->limit(0,9)->select();
				//判断是否从POIS中获取到信息
				if(count($list)>0){
					foreach($list as $val){
						if(intval($val['pois_id']) != intval($word_pois)){
							
							if(intval($val['shop_id'])>0){
								$url = $this->_CONFIG['site']['host'].'/mobile/shop/detail/shop_id/'.$val['shop_id'].'.html';
							}else{
								$url = $this->_CONFIG['site']['host'].'/mobile/near/detail/id/'.$val['uid'].'.html';
							}
							$distance = getDistanceCN($val['lat'],$val['lng'],$lat,$lng);
							if(!empty($val['telephone'])){
								$text.= "<a href=\"".$url."\">".$val['name']."</a>\n".$val['address']." (".$distance.")\n".$val['telephone']."\n\n\n";
							}else{
								$text.= "<a href=\"".$url."\">".$val['name']."</a>\n".$val['address']." (".$distance.")\n\n\n";
							}
						}
					}	
				}
				
				
				if(empty($ding) && count($list)==0){
					$text = '回禀圣上，臣翻阅了整个新华字典也没找到你要的东东。依臣所见，还是点击下面菜单试试吧！';
				}

				//发送信息到客户
				$this->weixin->response($text,'text');

            }
        } else {
            $keyword = D('Shopweixinkeyword')->checkKeyword($this->shop_id,$data['Content']);
            if ($keyword) {
                switch ($keyword['type']) {
                    case 'text':
                        $this->weixin->response($keyword['contents'], 'text');
                        break;
                    case 'news':
                        $content = array();
                        $content[] = array(
                            $keyword['title'],
                            $keyword['contents'],
                            $this->getImage($keyword['photo']),
                            $keyword['url'],
                        );
                        $this->weixin->response($content, 'news');
                        break;
                }
            } else {
                $text = '回禀圣上，臣翻阅了整个新华字典也没找到你要的东东。依臣所见，还是点击下面菜单试试吧！';
				$this->weixin->response($text,'text');
            }
            
        }
    }

    private function events() {
        $data['get'] = $_GET;
        $data['post'] = $_POST;
        $data['data'] = $this->weixin->request();
		$usrdata = $this->userCheck($data['data']);
		
        if (!empty($data['data'])) {
            $datas = explode('_', $data['data']['EventKey']);
            $id = $datas[1];
            if (!$detail = D('Weixinqrcode')->find($id)) {
                die();
            }
            $type = $detail['type'];
            if ($type == 1) {
                $shop_id = $detail['soure_id'];
                $shop = D('Shop')->find($shop_id);
                $content[] = array(
                    $shop['shop_name'],
                    $shop['addr'],
                    $this->getImage($shop['photo']),
                    __HOST__ . '/mobile/shop/detail/shop_id/' . $shop_id . '.html',
                );

                if (!empty($usrdata)) {
                    $user_id = $usrdata['user_id'];
                    $ymd = date('Y-m-d', time());
                    $ymdarr = explode('-', $ymd);
                    if (!$de = D('Census')->where(array('user_id' => $user_id))->find()) {
                        $datac = array(
                            'user_id' => $user_id,
                            'year' => $ymdarr[0],
                            'month' => $ymdarr[1],
                            'day' => $ymdarr[2],
                        );
                        D('Census')->add($datac);
                    }
                    if (!$fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $shop_id))->find()) {
                        $dataf = array(
                            'user_id' => $user_id,
                            'shop_id' => $shop_id,
                            'create_time' => time(),
                            'create_ip' => get_client_ip(),
                        );
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($shop_id, 'fans_num');
                    } else {
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id'=>$fans['favorites_id'],'closed'=>0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            } elseif ($type == 2) { //抢购
                $tuan_id = $detail['soure_id'];
                $tuan = D('Tuan')->find($tuan_id);
                $content[] = array(
                    $tuan['title'],
                    $tuan['intro'],
                    $this->getImage($tuan['photo']),
                    __HOST__ . '/mobile/tuan/detail/tuan_id/' . $tuan_id . '.html',
                );

				
                if (!empty($usrdata)) {
                    $user_id = $usrdata['user_id'];
                    $ymd = date('Y-m-d', NOW_TIME);
                    $ymdarr = explode('-', $ymd);
                    if (!$de = D('Census')->where(array('user_id' => $user_id))->find()) {
                        $datac = array(
                            'user_id' => $user_id,
                            'year' => $ymdarr[0],
                            'month' => $ymdarr[1],
                            'day' => $ymdarr[2],
                        );
                        D('Census')->add($datac);
                    }
                    if (!$fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' =>$tuan['shop_id']))->find()) {
                        $dataf = array(
                            'user_id' => $user_id,
                            'shop_id' => $tuan['shop_id'],
                            'create_time' => NOW_TIME,
                            'create_ip' => get_client_ip(),
                        );
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($tuan['shop_id'], 'fans_num');
                    } else {
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id'=>$fans['favorites_id'],'closed'=>0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            }elseif($type == 3){ //购物
                $goods_id = $detail['soure_id'];
                $goods = D('Goods')->find($goods_id);
                $shops = D('Shop')->find($goods['shop_id']); 
                $content[] = array(
                    $goods['title'],
                    $shops['shop_name'],
                    $this->getImage($goods['photo']),
                    __HOST__ . '/mobile/mall/detail/goods_id/' . $goods_id . '.html',
                );

                if (!empty($usrdata)) {
                    $user_id = $usrdata['user_id'];
                    $ymd = date('Y-m-d', NOW_TIME);
                    $ymdarr = explode('-', $ymd);
                    if (!$de = D('Census')->where(array('user_id' => $user_id))->find()) {
                        $datac = array(
                            'user_id' => $user_id,
                            'year' => $ymdarr[0],
                            'month' => $ymdarr[1],
                            'day' => $ymdarr[2],
                        );
                        D('Census')->add($datac);
                    }
                    if (!$fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' =>$goods['shop_id']))->find()) {
                        $dataf = array(
                            'user_id' => $user_id,
                            'shop_id' => $goods['shop_id'],
                            'create_time' => NOW_TIME,
                            'create_ip' => get_client_ip(),
                        );
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($goods['shop_id'], 'fans_num');
                    } else {
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id'=>$fans['favorites_id'],'closed'=>0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            }
        }
    }
	
	
	
	
    //响应用户的事件
    private function event($event,$data) {
        if ($this->shop_id == 0) {
			//关注公众号事件
			if ($event == 'subscribe') {
				
				$usrdata = $this->userCheck($data);
				$account = $usrdata['account'];
				$nickname = $usrdata['nickname'];

				//作出对用户的通知
				if ($this->_CONFIG['weixin']['type'] == 1) {
					//整理发送信息
					$text = $this->_CONFIG['weixin']['description'];
					$text = str_replace('|nickname|',$nickname,$text);
					$text = str_replace('|account|',$account,$text);
					$text = str_replace('|passwd|',$passwd,$text);
					//发送信息给用户
					$this->weixin->response($text, 'text');
				} else {
					
					//整理发送信息
					$text = $this->_CONFIG['weixin']['description'];
					$text = str_replace('|nickname|',$nickname,$text);
					$text = str_replace('|account|',$account,$text);
					$text = str_replace('|passwd|',$passwd,$text);

					$content[] = array(
						$this->_CONFIG['weixin']['title'],
						$text,
						$this->getImage($this->_CONFIG['weixin']['photo']),
						$this->_CONFIG['weixin']['linkurl'],
					);
					//发送信息给用户
					$this->weixin->response($content, 'news');
				}
				
			}
			
			//启动公众号定位事件
			if ($event == 'LOCATION') {
				$usrdata = $this->userCheck($data);
				if($usrdata['isnew'] == true){
					//新注册提示
					$text = $usrdata['nickname'].',欢迎您加入'.$this->_CONFIG['site']['sitename'].'微信公众平台，我们将热诚为您服务，您的帐号为：'.$usrdata['account'].'，密码为：'.$usrdata['passwd'].'。您可以重新修改帐号和密码信息。';
					$this->weixin->response($text, 'text');
				}

				$lat = $data['Latitude'];
				$lng = $data['Longitude'];
				
				$result = D('Users')->save(array('lat' => $lat,'lng' => $lng,'user_id' => $usrdata['user_id']));
				
			}

		} else {
            $data['get'] = $_GET;
            $data['post'] = $_POST;
            $data['data'] = $this->weixin->request();
            $weixin_msg = unserialize($this->shopdetails['weixin_msg']);
            if ($weixin_msg['type'] == 1) {

                $this->weixin->response($weixin_msg['description'], 'text');
            } else {
                $content[] = array(
                    $weixin_msg['title'],
                    $weixin_msg['description'],
                    $this->getImage($weixin_msg['photo']),
                    $this->_CONFIG['weixin']['linkurl'],
                );
                $this->weixin->response($content, 'news');
            }
        }
    }
	
	
	// 仅扫描二维码
    public function scan() {
        $data['data'] = $this->weixin->request();
        //file_put_contents('/www/web/bao_baocms_cn/public_html/Baocms/Lib/Action/Weixin/ccc.txt', var_export($data['data'], true));
        if (!empty($data['data'])) {
            $id = $data['data']['EventKey'];
            if (!$detail = D('Weixinqrcode')->find($id)) {
                die();
            }
            $type = $detail['type'];
            if ($type == 1) {
                $shop_id = $detail['soure_id'];
                $shop = D('Shop')->find($shop_id);
                $content[] = array(
                    $shop['shop_name'].$detail['type'],
                    $shop['addr'],
                    $this->getImage($shop['photo']),
                    __HOST__ . '/mobile/shop/detail/shop_id/' . $shop_id . '.html',
                );
                //file_put_contents('/www/web/bao_baocms_cn/public_html/Baocms/Lib/Action/Weixin/bbb.txt', var_export($content, true));
                $result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
                if (!empty($result)) {
                    $user_id = $result['uid'];
                    $ymd = date('Y-m-d', NOW_TIME);
                    $ymdarr = explode('-', $ymd);
                    if (!$fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $shop_id))->find()) {
                        $dataf = array(
                            'user_id' => $user_id,
                            'shop_id' => $shop_id,
                            'create_time' => NOW_TIME,
                            'create_ip' => get_client_ip(),
                        );
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($shop_id, 'fans_num');
                    } else {
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id'=>$fans['favorites_id'],'closed'=>0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            } elseif ($type == 2) { //抢购
                $tuan_id = $detail['soure_id'];
                $tuan = D('Tuan')->find($tuan_id);
                $content[] = array(
                    $tuan['title'],
                    $tuan['intro'],
                    $this->getImage($tuan['photo']),
                    __HOST__ . '/mobile/tuan/detail/tuan_id/' . $tuan_id . '.html',
                );
                $result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
                if (!empty($result)) {
                    $user_id = $result['uid'];
                    if (!$fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' =>$tuan['shop_id']))->find()) {
                        $dataf = array(
                            'user_id' => $user_id,
                            'shop_id' => $tuan['shop_id'],
                            'create_time' => NOW_TIME,
                            'create_ip' => get_client_ip(),
                        );
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($tuan['shop_id'], 'fans_num');
                    } else {
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id'=>$fans['favorites_id'],'closed'=>0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            }elseif($type == 3){ //购物
                $goods_id = $detail['soure_id'];
                $goods = D('Goods')->find($goods_id);
                $shops = D('Shop')->find($goods['shop_id']);
                $content[] = array(
                    $goods['title'],
                    $shops['shop_name'],
                    $this->getImage($goods['photo']),
                    __HOST__ . '/mobile/mall/detail/goods_id/' . $goods_id . '.html',
                );
                $result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
                if (!empty($result)) {
                    $user_id = $result['uid'];
                    if (!$fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' =>$goods['shop_id']))->find()) {
                        $dataf = array(
                            'user_id' => $user_id,
                            'shop_id' => $goods['shop_id'],
                            'create_time' => NOW_TIME,
                            'create_ip' => get_client_ip(),
                        );
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($goods['shop_id'], 'fans_num');
                    } else {
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id'=>$fans['favorites_id'],'closed'=>0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            }
        }
    }

    private function getImage($img) {
        return __HOST__ . '/attachs/' . $img;
    }

    private function userCheck($data) {
       
		import("@/Net.Curl");
		$this->curl = new Curl();
		
		//获取开放接口用户信息
		$openid = $data['FromUserName'];
		$token= D('Weixin')->getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid&lang=zh_CN";
		$json = $this->curl->get($url);
		$arr = json_decode($json,true);
		$con_data = array(
			'type' => 'weixin',
			'open_id' => $arr['openid'],
			'token'   => $token,
			'nickname'   => $arr['nickname']
		);

		//查询开放连接表信息
		$connect = D('Connect')->getConnectByOpenid($con_data['type'], $con_data['open_id']);
		if (empty($connect)) {
			$connect = $con_data;
			$connect['connect_id'] = D('Connect')->add($con_data);
		} else {
			D('Connect')->save(array('connect_id' => $connect['connect_id'], 'token' => $con_data['token'], 'nickname' => $con_data['nickname']));
		}

		//查询用户表信息
		$usrdata = D('Users')->find($connect['uid']);

		//是否新注册
		$usrdata['isnew'] = false;
		
		if(empty($usrdata['user_id'])){	//判断接口用户为空则注册新用户
			// 用户数据整理
			$host = explode('.',$this->_CONFIG['site']['host']);
			$account = uniqid().'@'.$host[1].'.'.$host[2];
			if ($connect['nickname']=='') {
				$nickname = $connect['type'] . $connect['connect_id'];
			}else{
				$nickname = $connect['nickname'];
			}
			$user = array(
				'account' => $account,
				'password' => rand(10000000, 999999999),
				'nickname' => $nickname,
				'ext0' => $account,
				'token' => $connect['token'],
				'create_time' => NOW_TIME,
				'create_ip' => get_client_ip(),
			);
			//注册用户资料
			if(!D('Passport')->register($user)){
				$this->error ('创建帐号失败');
			}
			
			
			// 注册第三方接口
			session('connect', $connect['connect_id']);
			D('Connect')->save(array('connect_id' => $connect['connect_id'], 'uid' => $con['uid']));
			
			//二次查询用户表
			$userid = session('uid');
			$usrdata = D('Users')->where(array('user_id' => $userid))->find();
			
			//是否新注册
			$usrdata['isnew'] = true;
			$usrdata['passwd'] = $user['password'];
		}
		
		return $usrdata;
    }

	
}
