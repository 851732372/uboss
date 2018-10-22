<?php
/* 
 * 黎明互联
 * https://www.liminghulian.com/
 */

class AlipaybaseAction extends AlipayrsaAction
{
    /**
     * 以下信息需要根据自己实际情况修改
     */
    const PID = '';//合作伙伴ID
    const KEY = ''; //安全码
    const PAYGAGEWAY = 'https://mapi.alipay.com/gateway.do';
    const CHECKURL = 'https://mapi.alipay.com/gateway.do?service=notify_verify&partner=' . self::PID . '&notify_id=';
    const NEW_PAYGATEWAY = 'https://openapi.alipay.com/gateway.do';
    //修改成自己的 应用私钥
    const APPPRIKEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6UTJrAfBr3c6D9T45K8I+ZZU9yKN23ZYscfiwmh30X/U3HeH7iIMXEB8b8pNYeqS0PhOxC9ZEQ+K6yNLI456L2Ehtp55a5UQDgqGQPjVKuLZVcFhj+4YZkiv9UKUTENaG5HQrRYudEMdZuYMpgZv1peLOO6QPGFowbyjMFceGCb+3gS7u2pSsaJ669fHpkdco7XKBBmwHqdd+7MHKs+Kmj52cWopRWeTvvIfj9khDQIbfIaw7C3HdtfpnC2PH/DOgVxauYbJ60dqUkq1xFYgAdJv0PbfF0S6oDgM+0BfVuW1548j0w/Q1FfAINmU8ay/wPbqMXTvivTw5udkwJs6PwIDAQAB';
    const APPID = '2018090161244107';     //修改成自己的 APPID
    //修改成自己的 支付宝公钥
    const NEW_ALIPUBKE = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk2Nx7iqgXKC1C8SW075wC969IJ20k3zZpgGL5RWYxqYms9+Q71VKcQehacIGURMGE62BAaMSjeWJyfGWtwSOlVhx6Zc9/eD2VkMBaINYu8Jvr60YTN1MUDrbrIVeuEs8n5wV00CKnOf06nB08NcP3MlXZ/Jn2RDj7JNGiLMHMpDwhGCQnFU8m/RfSiyXwuMjZe57N4YHN1FXZY4LgVW1PgZxD5UxfpIzlCG9zvtsZ4lcIHCL+BlWRksxem5B01HsETFHpcEBusTe2UhbmK3+w3LAoH1tZPLDPH79xbDTY6xo9+Dg6YB9VRIPAcTOUx0B4bqldMKsOHzwdl21ARFBcQIDAQAB';
    public function getStr($arr,$type = 'RSA'){
        //筛选  
        if(isset($arr['sign'])){
            unset($arr['sign']);
        }
        if(isset($arr['sign_type']) && $type == 'RSA'){
            unset($arr['sign_type']);
        }
        //排序  
        ksort($arr);
        //拼接
       return  $this->getUrl($arr,false);
    }
    //将数组转换为url格式的字符串
    public function getUrl($arr,$encode = true){
       if($encode){
            return http_build_query($arr);
       }else{
            return urldecode(http_build_query($arr));
       }
    }
    //获取签名MD5
    public function getSign($arr){
       return  md5($this->getStr($arr) . self::KEY );
    }
    //获取含有签名的数组MD5
    public function setSign($arr){
        $arr['sign'] = $this->getSign($arr);
        return $arr;
    }
    //获取签名RSA
    public function getRsaSign($arr){
       return $this->rsaSign($this->getStr($arr), self::APPPRIKEY) ;
    }
    //获取含有签名的数组RSA
    public function setRsaSign($arr){
        $arr['sign'] = $this->getRsaSign($arr);
        return $arr;
    }
    //获取签名RSA2
    public function getRsa2Sign($arr){
       return $this->rsaSign($this->getStr($arr,'RSA2'), self::APPPRIKEY,'RSA2') ;
    }
    //获取含有签名的数组RSA
    public function setRsa2Sign($arr){
        $arr['sign'] = $this->getRsa2Sign($arr);
        return $arr;
    }
    //记录日志
    public function logs($filename,$data){
        file_put_contents('./logs/' . $filename, $data . "\r\n",FILE_APPEND);
    }
    //2.验证签名
    public function checkSign($arr){
        $sign = $this->getSign($arr);
        if($sign == $arr['sign']){
            return true;
        }else{
            return false;
        }
    }
     
    //验证是否来之支付宝的通知
    public function isAlipay($arr){
        $str = file_get_contents(self::CHECKURL . $arr['notify_id']);
        if($str == 'true'){
            return true;
        }else{
            return false;
        }
    }
    // 4.验证交易状态
    public function checkOrderStatus($arr){
        if($arr['trade_status'] == 'TRADE_SUCCESS' || $arr['trade_status'] == 'TRADE_FINISHED'){
            return true;
        } else {
            return false;
        }
    }
    
    /**
      使用curl方式实现get或post请求
      @param $url 请求的url地址
      @param $data 发送的post数据 如果为空则为get方式请求
      return 请求后获取到的数据
    */
    public function curlRequest($url,$data = ''){
            $ch = curl_init();
            $params[CURLOPT_URL] = $url;    //请求url地址
            $params[CURLOPT_HEADER] = false; //是否返回响应头信息
            $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
            $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
                    $params[CURLOPT_TIMEOUT] = 30; //超时时间
                    if(!empty($data)){
                            $params[CURLOPT_POST] = true;
                            $params[CURLOPT_POSTFIELDS] = $data;
            }
                    $params[CURLOPT_SSL_VERIFYPEER] = false;//请求https时设置,还有其他解决方案
                    $params[CURLOPT_SSL_VERIFYHOST] = false;//请求https时,其他方案查看其他博文
            curl_setopt_array($ch, $params); //传入curl参数
            $content = curl_exec($ch); //执行
            curl_close($ch); //关闭连接
            return $content;
    }
}