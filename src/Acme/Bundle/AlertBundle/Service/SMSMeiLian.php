<?php

namespace Acme\Bundle\AlertBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SMSMeiLian
{
    private $container;    
    private $logger;

    private $platformUrl;
    private $smsAccount;
    private $smsPasswordMd5;
    private $smsApiKey;

    private $smsVoiceAccount;
    private $smsVoicePasswordMd5;
    private $smsVoiceApiKey;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->init();        
    }

    private function init()
    {
        $this->platformUrl = $this->container->getParameter('sms_platform_url');
        $this->smsAccount = $this->container->getParameter('sms_txt_account');
        $this->smsPasswordMd5 = $this->container->getParameter('sms_txt_password_md5');
        $this->smsApiKey = $this->container->getParameter('sms_txt_api_key');

        $this->smsVoiceAccount = $this->container->getParameter('sms_voice_accout');
        $this->smsVoicePasswordMd5 = $this->container->getParameter('sms_voice_password_md5');
        $this->smsVoiceApiKey = $this->container->getParameter('sms_voice_api_key');
    }

    /*
     * $mobile 只发一个号码：13800000001。发多个号码：13800000001,13800000002,...N
     * Return the result from platform, success or error.
     */
    public function sendSMSText($mobile, $content)
    {        
        $url = $this->platformUrl;
        $encode='UTF-8';

        $contentUrlEncode = urlencode($content);

        $data = array(
                        'username'     => $this->smsAccount,
                        'password_md5' => $this->smsPasswordMd5,
                        'apikey'       => $this->smsApiKey,
                        'mobile'       => $mobile,
                        'content'      => $contentUrlEncode,
                        'encode'       => $encode
                      );
        $result = $this->curlSMS($url, $data);

        return $result;
    }

    /*
     * $mobile 只发一个号码：13800000001。发多个号码：13800000001,13800000002,...N
     * Return the result from platform, success or error.
     */
    public function sendSMSVoice($mobile, $content)
    {        
        $url = $this->platformUrl;
        $encode = 'UTF-8';

        $contentUrlEncode = urlencode($content);

        $data = array(
                        'username'     => $this->smsVoiceAccount,
                        'password_md5' => $this->smsVoicePasswordMd5,
                        'apikey'       => $this->smsVoiceApiKey,
                        'mobile'       => $mobile,
                        'content'      => $contentUrlEncode,
                        'encode'       => $encode
                      );

        $result = $this->curlSMS($url, $data);

        return $result;
    }

    private function curlSMS($url, $post_fields = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//用PHP取回的URL地址（值将被作为字符串）
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//使用curl_setopt获取页面内容或提交数据，有时候希望返回的内容作为变量存储，而不是直接输出，这时候希望返回的内容作为变量
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);//30秒超时限制
        curl_setopt($ch, CURLOPT_HEADER, 1);//将文件头输出直接可见。
        curl_setopt($ch, CURLOPT_POST, 1);//设置这个选项为一个零非值，这个post是普通的application/x-www-from-urlencoded类型，多数被HTTP表调用。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);//post操作的所有数据的字符串。
        $data = curl_exec($ch);//抓取URL并把他传递给浏览器
        curl_close($ch);//释放资源
        $res = explode("\r\n\r\n", $data);//explode把他打散成为数组
        return $res[2]; //然后在这里返回数组。
    }
}
