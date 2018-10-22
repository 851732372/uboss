<?php

/**
 * File name: SmsAcion.php
 * 短信验证码发送类
 * Created on: 2018/9/20 14:52
 * Created by: Ginger.
 */
class SmsAction extends BaseAction
{
    // 保存错误信息
    public $error;
    // Access Key ID
    private $accessKeyId = '';
    // Access Access Key Secret
    private $accessKeySecret = '';
    // 签名
    private $signName = '';
    // 模版ID
    private $templateCode = '';
    public function __construct($config = array())
    {
        $config = array (
            'accessKeyId' => 'LTAIxfk0yOzScOfi',
            'accessKeySecret' => 'gBTbIB4TPcFMLtLe1n9MB3t7sy7PW0',
            'signName' => '优BOSS',
            'templateCode' => 'SMS_145230678'
        );

        $this->accessKeyId = $config ['accessKeyId'];
        $this->accessKeySecret = $config ['accessKeySecret'];
        $this->signName = $config ['signName'];
        $this->templateCode = $config ['templateCode'];
    }

    /**
     * 配置参数
     * @author Ginger
     * @param string
     * return string
     */
    private function percentEncode($string)
    {
        $string = urlencode ( $string );
        $string = preg_replace ( '/\+/', '%20', $string );
        $string = preg_replace ( '/\*/', '%2A', $string );
        $string = preg_replace ( '/%7E/', '~', $string );
        return $string;
    }

    /**
     * 签名
     * @author Ginger
     * @param $parameters
     * @param $accessKeySecret
     * return
     */
    private function computeSignature($parameters, $accessKeySecret)
    {
        ksort ( $parameters );
        $canonicalizedQueryString = '';
        foreach ( $parameters as $key => $value )
        {
            $canonicalizedQueryString .= '&' . $this->percentEncode ( $key ) . '=' . $this->percentEncode ( $value );
        }

        $stringToSign = 'GET&%2F&' . $this->percentencode ( substr ( $canonicalizedQueryString, 1 ) );
        $signature = base64_encode ( hash_hmac ( 'sha1', $stringToSign, $accessKeySecret . '&', true ) );
        return $signature;
    }

    /**
     * 发送短信
     * @author Ginger
     * @param string $mobile
     * @param string $verify_code
     * return
     */
    public function send($mobile = '18792598172', $verify_code = '2222', $templateCode = 'SMS_145230678')
    {
        $params = array (
            'SignName' => $this->signName,
            'Format' => 'JSON',
            'Version' => '2017-05-25',
            'AccessKeyId' => $this->accessKeyId,
            'SignatureVersion' => '1.0',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureNonce' => uniqid (),
            'Timestamp' => gmdate ( 'Y-m-d\TH:i:s\Z' ),
            'Action' => 'SendSms',
            'TemplateCode' => $templateCode,//$this->templateCode,
            'PhoneNumbers' => $mobile,
            'TemplateParam' => '{"code":"' . $verify_code . '"}'
        );
        $params ['Signature'] = $this->computeSignature ( $params, $this->accessKeySecret );
        $url = 'http://dysmsapi.aliyuncs.com/?' . http_build_query ( $params );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        $result = json_decode ( $result, true );
        if ($result ['Code'] == 'OK')
        {
            return true;
        }
        else
        {
            $this->error = $this->getErrorMessage ( $result ['Code'] );
            return false;
        }
    }


    /**
     * 错误信息
     * @author Ginger
     * @param $status
     * return
     */
    public function getErrorMessage($status)
    {
        $message = array (
            'InvalidDayuStatus.Malformed' => '账户短信开通状态不正确',
            'InvalidSignName.Malformed' => '短信签名不正确或签名状态不正确',
            'InvalidTemplateCode.MalFormed' => '短信模板Code不正确或者模板状态不正确',
            'InvalidRecNum.Malformed' => '目标手机号不正确，单次发送数量不能超过100',
            'InvalidParamString.MalFormed' => '短信模板中变量不是json格式',
            'InvalidParamStringTemplate.Malformed' => '短信模板中变量与模板内容不匹配',
            'InvalidSendSms' => '触发业务流控',
            'InvalidDayu.Malformed' => '变量不能是url，可以将变量固化在模板中'
        );
        if (isset ( $message [$status] ))
        {
            return $message [$status];
        }
        return $status;
    }
}