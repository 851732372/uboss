<?php

/**
 * File name: CashAction.class.php
 * 文件描述
 * Created on: 2018/10/15 11:05
 * Created by: Ginger.
 */
class CashAction extends BaseAction
{
    public function cash()
    {
        //公众账号appid
        $data["mch_appid"] = 'wxece7fccd7def1e55';
//商户号
        $data["mchid"] = '1488972442';
//随机字符串
        $data["nonce_str"] = 'suiji'.mt_rand(100,999);
//商户订单号
        $data["partner_trade_no"]=date('YmdHis').mt_rand(1000,9999);
//金额 用户输入的提现金额需要乘以100
        $data["amount"] = 200;
//企业付款描述
        $data["desc"] = '企业付款到个人零钱';
//用户openid
        $data["openid"] = 'oKJ2709WhceYX26yMtHjZTh6RdeA';
//不检验用户姓名
        $data["check_name"] = 'NO_CHECK';
//获取IP
        $data['spbill_create_ip']=$_SERVER['SERVER_ADDR'];
//商户密钥
        $data['key']='';
//商户证书 商户平台的API安全证书下载
        $data['apiclient_cert.pem'];
        $data['apiclient_key.pem'];
    }
    /**
     **开始支付
     */
    public function userpay(){
        $money = 3;
        $info['money'] = 5;
        if ('oKJ2709WhceYX26yMtHjZTh6RdeA' && $money){
            if ($money>$info['money'] ){
                echo json_encode([
                    'status' => 1,
                    'message' => '余额不足，不能提现！',
                    'code'=>'余额不足，不能提现！'
                ]);
            }elseif ($money<1){
                echo json_encode([
                    'status' => 2,
                    'message' => '提现金额不能小于1元',
                    'code'=>'提现金额太低'
                ]);
            }else{
                $openid = 'oKJ2709WhceYX26yMtHjZTh6RdeA';
                $trade_no = date('YmdHis').mt_rand(1000,9999);
                $res = $this->pay($openid,$trade_no,$money*100,'微信提现');
                echo json_encode($res);exit();
                //结果打印
                if($res['result_code']=="SUCCESS"){

                    echo json_encode([
                        'status' => 3,
                        'message' => '提现成功！',
                    ]);
                }elseif ($res['err_code']=="SENDNUM_LIMIT"){
                    echo json_encode([
                        'status' => 4,
                        'message' => '提现失败！',
                        'code'=>'每日仅能提现一次',
                    ]);
                }else{
                    echo json_encode([
                        'status' => 5,
                        'message' => '提现失败！',
                        'code'=>$res['err_code'],
                    ]);
                }
            }
        }else{
            echo json_encode([
                'status' => 5,
                'message' => '未检测到您当前微信账号~',

            ]);
        }
    }
    /**
     *支付方法
     */
    public function pay($openid,$trade_no,$money,$desc){
        $params["mch_appid"]='wxece7fccd7def1e55';
        $params["mchid"] = '1488972442';
        $params["nonce_str"]= 'suiji'.mt_rand(100,999);
        $params["partner_trade_no"] = $trade_no;
        $params["amount"]= $money;
        $params["desc"]= $desc;
        $params["openid"]= $openid;
        $params["check_name"]= 'NO_CHECK';
        $params['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];

        //生成签名
        $str = 'amount='.$params["amount"].'&check_name='.$params["check_name"].'&desc='.$params["desc"].'&mch_appid='.$params["mch_appid"].'&mchid='.$params["mchid"].'&nonce_str='.$params["nonce_str"].'&openid='.$params["openid"].'&partner_trade_no='.$params["partner_trade_no"].'&spbill_create_ip='.$params['spbill_create_ip'].'&key=商户密钥';

        //md5加密 转换成大写
        $sign = strtoupper(md5($str));
        //生成签名
        $params['sign'] = $sign;

        //构造XML数据
        $xmldata = $this->array_to_xml($params); //数组转XML
        $url='https://api.mch.weixin.qq.com/mmpaymkttransfers/prom otion/transfers';

        //发送post请求
        $res = $this->curl_post_ssl($url, $xmldata); //curl请求
        if(!$res){
            return array('status'=>1,
                'msg'=>"服务器连接失败" );
        }

        //付款结果分析
        $content = $this->xml_to_array($res); //xml转数组
        return $content;
    }
    /**
     * curl请求
     */
    public function curl_post_ssl($url, $xmldata,  $second=30,$aHeader=array()){
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        //默认格式为PEM，可以注释
//        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//        //绝对地址可使用 dirname(__DIR__)打印，如果不是绝对地址会报 58 错误
//        curl_setopt($ch,CURLOPT_SSLCERT,' 绝对地址/apiclient_cert.pem');
//        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//        curl_setopt($ch,CURLOPT_SSLKEY,'绝对地址/apiclient_key.pem');
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xmldata);
        $data = curl_exec($ch);
        echo $data;
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            die();
            curl_close($ch);
            return false;
        }
    }
    /**
     * array 转 xml
     * 用于生成签名
     */
    public function array_to_xml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" .$key.">".$val."</".$key.">";
            } else
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml .= "</xml>";
        return $xml;
    }
    /**
     * xml 转化为array
     */
    public function xml_to_array($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }



//    public function alicash()
//    {
//        $out_biz_no = Tools::buildOrderNo();
////        $res = $this->userWithDraw($cash_id,$approve_status,$out_biz_no,$payee_account,$amount);
//    }
//    private function userWithDraw($cash_id,$approve_status,$out_biz_no,$payee_account,$amount)
//    {
//        $ret = false;
//        include(EXTEND_PATH.'/alipay/AopSdk.php');
//        $payer_show_name = '付款人姓名';
//        $remark = "您在XXX商城申请提现受理成功，商户订单号【".$out_biz_no."】,提现金额为".$amount."元,请在支付宝余额进行查看";
//        $aop = new \AopClient();
//        $aop->gatewayUrl =  'https://openapi.alipay.com/gateway.do';//支付宝网关 https://openapi.alipay.com/gateway.do这个是不变的
//        $aop->appId = Config::get('custom.alipay.appId');//商户appid 在支付宝控制台找
//        $aop->rsaPrivateKey = Config::get('custom.alipay.rsaPrivateKey');//私钥 工具生成的
//        $aop->alipayrsaPublicKey = Config::get('custom.alipay.alipayrsaPublicKey');//支付宝公钥 上传应用公钥后 支付宝生成的支付宝公钥
//        $aop->apiVersion = '1.0';
//        $aop->signType = 'RSA2';
//        $aop->postCharset='utf-8';
//        $aop->format='json';
//        $request = new \AlipayFundTransToaccountTransferRequest();
//        $request->setBizContent("{" .
//            "\"out_biz_no\":\"$out_biz_no\"," .
//            "\"payee_type\":\"ALIPAY_LOGONID\"," .
//            "\"payee_account\":\"$payee_account\"," .
//            "\"amount\":\"$amount\"," .
//            "\"payer_show_name\":\"$payer_show_name\"," .
//            "\"remark\":\"$remark\"" .
//            "}");
//
//        $result = $aop->execute($request);
//
//        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//        $resultCode = $result->$responseNode->code;
//
//        if(!empty($resultCode)&&$resultCode == 10000){
//            //提现成功以后 更新表状态
//            //并且记录 流水等等
//            $cash_status = 1;
//            F_PC::approveStatus($cash_id, $approve_status);
//            $res = F_PC::cashStatus($cash_id, $cash_status);
//            if (!$res) {
//                //提现成功，状态修改失败
//                $this->code = RetCode::FAILED;
//                $this->mesg = '提现成功，状态修改失败';
//                goto ret;
//            }
//            goto ret;
//            ret:
//            $res = $ret ? true : false;
//            return $res;
//        } else {
//            //$result->$responseNode->sub_msg 这个参数 是返回的错误信息
//            // $errorCode=json_decode(json_encode($result->$responseNode),TRUE);
//            //提现失败   写入修改状态提现失败
//            $cash_status = 2;
//            $res = F_PC::cashStatus($cash_id, $cash_status);
//            if (!$res) {
//                //状态修改失败
//                $this->code = RetCode::FAILED;
//                $this->mesg = '状态修改失败';
//                goto ret;
//            }
//            goto ret;
//
//            $res = $ret ? true : false;
//            return $res;
//        }
//    }


//    public function alicashTest()
//    {
//        //公共请求参数
//        $pub_params = [
//            'app_id'    => self::APPID,
//            'method'    =>  'alipay.fund.trans.toaccount.transfer', //接口名称 应填写固定值alipay.fund.trans.toaccount.transfer
//            'format'    =>  'JSON', //目前仅支持JSON
//            'charset'    =>  'UTF-8',
//            'sign_type'    =>  'RSA2',//签名方式
//            'sign'    =>  '', //签名
//            'timestamp'    => date('Y-m-d H:i:s'), //发送时间 格式0000-00-00 00:00:00
//            'version'    =>  '1.0', //固定为1.0
//            'biz_content'    =>  '', //业务请求参数的集合
//        ];
//
//        //请求参数
//        $api_params = [
//            'out_biz_no'  => date('YmdHis'),//商户转账订单号
//            'payee_type'  => 'ALIPAY_LOGONID', //收款方账户类型
//            'payee_account'  => $data['payee_account'], //收款方账户
//            'amount'  => $data['amount'], //金额
//        ];
//        //请求参数转换为json后和业务参数合并
//        $pub_params['biz_content'] = json_encode($api_params,JSON_UNESCAPED_UNICODE);
//    }


    public function ginger()
    {
        $this->userWithDraw(1, 'ginger0828', '18792598172', 100, '姜鹏军');
    }
    /**
     * @User 一秋
     * @param $userid  用户id
     * @param $out_biz_no 编号
     * @param $payee_account 提现的支付宝账号
     * @param $amount 转账金额
     * @param $payee_real_name 账号的真实姓名
     * @return bool|Exception
     */
    public function userWithDraw($userid,$out_biz_no,$payee_account,$amount,$payee_real_name)
    {
        $config['appId'] = '2018090161244107';'2018080460989135';
        $config['gatewayUrl'] = 'https://openapi.alipay.com/gateway.do';
        $config['rsaPrivateKey'] = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCdYguY2k1nmsey2R2GTLyQCop54HbdhB9Xf/FFuGcKY+Skx/UZMMWgfdAm+LUWmcjO4x5Z6AXj4Z4s5btDfpPj4wjHm9m1Mo/jg8cr47UpTQ47apeqILcMk7Ik8tgVlcKQ6opPYE4S3+pg+HvVOqAfmYLOaLujkxNB5T4U6E18RG0XNgsKYWR7vq1JRVC/fmGKIAKXMlpF2Ap+h8WTrCAxoBYQ04xswCKKsrXHo/G6fFOiBjG1yMuJd82GyJ5ynFGZLTFHdBxsC3Xj2QQ57kCpcIybLPcVheSHX3ADh65GX5i8Mxs/iLCn6wpqcXcY5702OehFMUEU8+dVCWvIj6d1AgMBAAECggEBAJNaxkLyOkED4XAp6oPg+zfeNrNQH9rdpr61MwC1W/yVdPF3qptQV+khgy/J8zta93IFolhJbqJjj0a4PfI+5FcdLg4+dMM7uQ8rReNpCyjFVcr5zVDj2p9y9fu0R+9FzesDPkZ9g/wWHIt4e/E0N9H+WEPMOcs4nHI5IjxPGKV8rV8mVAaBXtwjeo/64Tnvj8XcUFAGh0ShoHCvPkwrTq9n8RTFl3FVEtuTBsGfzeBwx/sW3X2J1lSfaswijWLEyvzHVMz5BqVwAtDcfL1gcCoQ6SBiK+4IEMtOsWUaSbh8WwOMGfDNmRxdyYhwUca8j2TAE9BSmfGxy5s8hNItRqECgYEA4NrUOdjM/YX4K/vTnBGyNxQ7+ZfE/9j5M2rDYvdIZaZTVVzCN4a6q3EJoOLUt23F1eXOWSnkGNDQQBHYf8AQm+q5O9o6oOqFY4YwCkA6BiNPNm4B1l+h818B+jKY7G5CR9z2YLh5DPEcfnT6yuJ4ZMikrosviDBrD+MbHBg/+v0CgYEAsy64TosEnrVzGm9cZfp9GlirZAjDy+ZRmIIKFl+nn/tlP7a20YrdTwM9jwPHwfkcZ12GtRYwzgvTMteWnoxJl6UF2RjolHtiSHLMaUPQs1Ge59ZmyEd/nA0FAY0DZ8M8wzk/kVd5ABZRS17lRKd8u6EbBBj+aabvgoTgJaG5s9kCgYAoBowUqAUFdB2TJsaqV3QT3eQ3L87/hA4IGw8gxMf8YDkhTtu7qp37+qkuHHKlHENFKh0rPgu+Zx1K6mJRi0zplETY5KXfEyAfcTmH+ZrakNp+4+Ir2OYpPlZJxUv3ekEOLcUlvnC93wWldQlXqCyqNBUshNY3Nfc44sJmo+JvuQKBgFIcued8e5yDiID3wX8WQFhfhANupNN+86uRBDBmm3mMCkUdZUA3g92enYo5cERq9WJFEONRjQIPlzR4aZS0s1DkFf78FDFfLZYHzbb7ldtnLwwNS8LCSS9pI/8m8QWPCfxo8sGXNWMd1xlUHnrMyjNty+Sf0471KlNEszdrmp9ZAoGAe3BvwzlKoOLowfSjDlNHKBtEDYDNQAoSyYFRwj76Lo3GPC5wrEI/ZXSrMMeeCzE85/086AVJ29rJag9wUbcIjfMsMvRx3EuKagyzLnOjZfVQlER7LI6vJp6OK8ATbAq7WBM59IglK4OSwyw5hJSjlNFZ9U8GyWscJRWB0HzhYe0=';
            //'MIIEpQIBAAKCAQEAvxyJlX4mZYKOqP3ga7TPjgCIhKikaHfRmHySGQkH+lOOEG/ck/tldJRPRrtjtCBxRndYIiCWO5fHrvU3evefuU2hfVOa/lcwm6T5ezj8XKoPM91V472HHAM7FrXGEpklHWcdhQw0OCoiwIhv6U69mLW5H6FB+4IZ4v3Zr4Dp5uIYZrVt1kbqzfyUE8793ga4twzEvbtCIS6JW617aMvf7ttwNiFfm1/B+F0d9QzPXp4H9+nc8+PDviOvrMhE+Jt90Mf/452ynJBXncdxovPqGk3mEHrmRExEyNSu2ORFV5VqmlT6Dg4ZI3+ebNZBVS3C+oTW5NeKl9Xs06AwjjubcwIDAQABAoIBAQCy26tsDIc0445OXGPniW3ikV8zAH32A4VWd+dnpMshnYdMnNRMbrCR/aeAJCp4zxAUieci8WqI1VEdzgOhgxSqIRxtJYzeUo18bVbL2xZcRxA5UwFqRdun/OIVLAP3LfrNXM8E0NoiYlTp6gcKzBWnUROzpYESOL2vcjKEDVtbJ0gEuGB68kex8sKYvrKkGOnksn4lMuJgCp+UiIIJ4NVHKYc7KtTdM/M1lrIviP0lspvPnrKbUOwKRxWm1Lqxt27zJpkAwZ/I+04wYRm5L3BkxpDjWHA0a60hpHvfa2kcM+pzq7DlRDqbPYE4isyNLz+E3njELex9TwPk53tFILGBAoGBAPk2oC4Hxt+Wwleongn5XsHmIVlft0WQ+boKfwtYhxSywl3hUbjsDKw5/9ZPricR38XsozIF0jkRQP09+raQrrXex674GbWkasQAbZT1Zxpuyt5Rmv5y2bCDJyUqXnxZuePdxC4qpITXcv6dKw4doFpuvyfbsjWKP8aZZRY6bOdBAoGBAMRQ27M3C+e4hYQ9RiZa86kCfpAtYeDDDkIN4OYQSCLuM//TcJWXz2VKMPR+nHKa62McsxTJKm7JAP/D7Q7T5o0KztoMdxFn6IJQyR9g05rTGKh/9zwavXAq4Ba2/oXIVSXqHRrNcwmjJuE0wyP9tWHRPJ4f/HSW3njaMtJAbKmzAoGAba/iToAzwwNK5GQdswwj7bnJYdnkA2Vs1Lp78lVzluOa8tESNA8JAODCPuRxJVcTo7ykk4bGYUG71fpql+IUU44LW3Jtdmo4Z648jPx59cNf3PwT+Pw53hvLpesBDUA8thNMnnk8Ug+12GT6p0rY4hSuY++pfzErItEvEdqDRkECgYEAo4oEa5hIt6F0wIPW427TGpE0Y+Eab4WuZAJxvtYDce3jrZMzb7sGj4etno+2vpYlzuwClyc5zQ5vYaGS4s5NemNtjCHkMsDP5XdtBFHCFXETUpM/n3dbJozhHqsU/tezbyFHW6OY2IdshSFSCrJ4nmMSz1swEOP5ss16lsIKg5cCgYEAk8uHYDcv+porN3E8pMOyblrLUYvck7UtbEO/D5epvRIirc18iPaszguAvJbw68jP+rLFKsmx7Djgq1N9kzmnOQSvWPLWUPtMt5/6pu41m6/nqqn3gMj67zOCssbYeLrFmOcE9i5s2ldzflMiNnLRWyWBCZB+leK6ezAFiliblZk=';
        $config['alipayrsaPublicKey'] = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk2Nx7iqgXKC1C8SW075wC969IJ20k3zZpgGL5RWYxqYms9+Q71VKcQehacIGURMGE62BAaMSjeWJyfGWtwSOlVhx6Zc9/eD2VkMBaINYu8Jvr60YTN1MUDrbrIVeuEs8n5wV00CKnOf06nB08NcP3MlXZ/Jn2RDj7JNGiLMHMpDwhGCQnFU8m/RfSiyXwuMjZe57N4YHN1FXZY4LgVW1PgZxD5UxfpIzlCG9zvtsZ4lcIHCL+BlWRksxem5B01HsETFHpcEBusTe2UhbmK3+w3LAoH1tZPLDPH79xbDTY6xo9+Dg6YB9VRIPAcTOUx0B4bqldMKsOHzwdl21ARFBcQIDAQAB';
            //'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk2Nx7iqgXKC1C8SW075wC969IJ20k3zZpgGL5RWYxqYms9+Q71VKcQehacIGURMGE62BAaMSjeWJyfGWtwSOlVhx6Zc9/eD2VkMBaINYu8Jvr60YTN1MUDrbrIVeuEs8n5wV00CKnOf06nB08NcP3MlXZ/Jn2RDj7JNGiLMHMpDwhGCQnFU8m/RfSiyXwuMjZe57N4YHN1FXZY4LgVW1PgZxD5UxfpIzlCG9zvtsZ4lcIHCL+BlWRksxem5B01HsETFHpcEBusTe2UhbmK3+w3LAoH1tZPLDPH79xbDTY6xo9+Dg6YB9VRIPAcTOUx0B4bqldMKsOHzwdl21ARFBcQIDAQAB';

        require_cache(APP_PATH . 'Extend/aop/AopClient.php');
        require_cache(APP_PATH . 'Extend/aop/request/AlipayFundTransToaccountTransferRequest.php');
        require_cache(APP_PATH . 'Extend/aop/SignData.php');

        $payer_show_name = '用户提现';
        $remark = '提现到支付宝';
        $aop = new AopClient();
        $aop->appId = $config['appId']; //商户appid 在支付宝控制台找
        $aop->gatewayUrl = $config['gatewayUrl'];   //支付宝网关 https://openapi.alipay.com/gateway.do这个是不变的
        $aop->rsaPrivateKey = $config['rsaPrivateKey']; //私钥 工具生成的
        $aop->alipayrsaPublicKey = $config['alipayrsaPublicKey'];   //支付宝公钥 上传应用公钥后 支付宝生成的支付宝公钥
        $aop->apiVersion = '3.3.0';
        $aop->signType = 'RSA2';
        $aop->postCharset ='utf-8';
        $aop->format = 'json';
        $request = new AlipayFundTransToaccountTransferRequest();
        $request->setBizContent("{" .
            "\"out_biz_no\":\"$out_biz_no\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\"$payee_account\"," .
            "\"amount\":\"$amount\"," .
            "\"payer_show_name\":\"$payer_show_name\"," .
            "\"payee_real_name\":\"$payee_real_name\"," .
            "\"remark\":\"$remark\"" .
            "}");
        $result = $aop->execute ($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        var_dump($result);return;
        if(!empty($resultCode) && $resultCode == 10000)
        {
            //提现成功以后 更新表状态
            //并且记录 流水等等
            return outMessage(1, '提现成功');
            return true;
        }
        else
        {
            //$result->$responseNode->sub_msg 这个参数 是返回的错误信息
//            echo $result->$responseNode->sub_msg;
//            return;
            return outMessage(-1, $result->$responseNode->sub_msg);
        }
    }


    public function djk()
    {
        
    }
}