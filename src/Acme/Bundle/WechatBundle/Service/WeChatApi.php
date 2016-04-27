<?php

namespace Acme\Bundle\WechatBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class WeChatApi
{
    private $container;
    private $logger;

    private $appid;
    private $appsecret;

    private $jsTicketSignatureUrl;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $this->container->get("my_service.logger");

        $this->appid = $this->container->getParameter("wechat_appid");
        $this->jsTicketSignatureUrl = $this->container->getParameter("wechat_js_signature_url");
    }

    public function checkSignatureValid(Request $request)
    {
        $signature = $request->get("signature");
        $timestamp = $request->get("timestamp");
        $nonce = $request->get("nonce");
        $echoStr = $request->get("echostr");

        $token = $this->container->getParameter("wechat_token");
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if($tmpStr === $signature){
            $result = $echoStr;
        } else {
            $result = "error: signature not matched!";
        }

        $info = sprintf("signature: \"%s\", timestamp: \"%s\", nonce: \"%s\", echostr: \"%s\"", $signature, $timestamp, $nonce, $echoStr);
        $this->toLog($info);

        return $result;
    }

    public function getAccessToken()
    {
        $access_token = "";
        $access_token_file_path = $this->container->getParameter("wechat_access_token_file");

        if(file_exists($access_token_file_path)) {
            $content = file_get_contents($access_token_file_path);

            $ret = $this->accessTokenJsonDecode($content);

            // Note this will cause no access_token / expires_in key exception
            $access_token = $ret->{"access_token"};
            $expires_time = $ret->{"expires_time"};

            // $expires_time_window = $this->container->getParameter("wechat_access_token_expires_in");
            $now = time();
            if($expires_time < $now){
                $info = sprintf("access: expires: \"%s\"", $expires_time);
                $access_token = $this->updateAccessToken();
            } else {
                $info = sprintf("access: in 2 hours token: \"%s\"", $access_token);
            }
        } else {
            $info = sprintf("access token file not exists: \"%s\"", $access_token_file_path);
            $access_token = $this->updateAccessToken();
        }

        $this->toLog($info);

        return $access_token;
    }

    public function getJsTicket()
    {
        $js_ticket = $info = "";
        $js_ticket_file_path = $this->container->getParameter("wechat_js_ticket_file");

        if(file_exists($js_ticket_file_path)) {
            $content = file_get_contents($js_ticket_file_path);

            $ret = $this->accessTokenJsonDecode($content);

            $js_ticket = $ret->{"ticket"};
            $expires_time = $ret->{"expires_time"};

            $now = time();
            if($expires_time < $now){
                $info = sprintf("js_ticket: expires: \"%s\"", $expires_time);
                $js_ticket = $this->updateJsTicket();
            } else {
                $info = sprintf("js_ticket: in 2 hours ticket: \"%s\"", $js_ticket);
            }
        } else {
            $info = sprintf("js_ticket file not exists: \"%s\"", $js_ticket_file_path);
            $js_ticket = $this->updateJsTicket();
        }

        $this->toLog($info);

        return $js_ticket;
    }

    public function getJsTicketSignatureList()
    {
        return $this->getJsTicketList($this->jsTicketSignatureUrl);
    }

    public function getJsTicketListWithUrl($url)
    {
        return $this->getJsTicketList($url);
    }

    private function getJsTicketList($js_ticket_url)
    {
        $nonce_str = $this->getRandChar(16);
        $timestamp = time();
        $appid = $this->appid;
        $js_ticket = $this->getJsTicket();
        $js_ticket_signature_url = $js_ticket_url;

        $param_array = array('noncestr=' . $nonce_str, 'jsapi_ticket=' . $js_ticket, 'timestamp=' . $timestamp, 'url=' . $js_ticket_signature_url);        
        sort($param_array, SORT_STRING);
        $tmp_str = implode("&",$param_array);
        // echo $tmp_str . '</br>';

        $signature = sha1( $tmp_str );
        // echo $signature;

        $this->toLog("====build string: " . $tmp_str);
        $this->toLog("====signature: " . $signature);

        return array($appid, $timestamp, $nonce_str, $signature);
    }

    private function updateAccessToken()
    {
        $access_token_url = $this->container->getParameter("wechat_access_token_url");
        $appid = $this->container->getParameter("wechat_appid");
        $appsecret = $this->container->getParameter("wechat_appsecret");
        $expires_time_window = $this->container->getParameter('wechat_access_token_expires_time_window');

        // $url = sprintf($access_token_url, $appid, $appsecret);
        $url = $access_token_url . "&appid=" . $appid . "&secret=" . $appsecret;

        $retJson = file_get_contents($url);

        $ret = $this->accessTokenJsonDecode($retJson);

        $access_token = $ret->{"access_token"};
        $expires_in = $ret->{"expires_in"};
        $ret->{"expires_time"} = time() + $expires_in;

        // $this->container->setParameter("wechat_access_token", $access_token);
        // $this->container->setParameter("wechat_access_token_expires_in", (int)$access_token + $expires_time_window);

        $access_token_file_path = $this->container->getParameter("wechat_access_token_file");
        $this->file_force_contents($access_token_file_path, json_encode($ret));

        $info = sprintf("access token update: url: \"%s\", token: \"%s\", expires: \"%s\"", $url, $access_token, $expires_in);

        return $access_token;
    }

    private function updateJsTicket()
    {
        $js_ticket_url = $this->container->getParameter("wechat_js_ticket_url");
        $webchat_access_token = $this->getAccessToken();

        $url = sprintf($js_ticket_url, $webchat_access_token);

        $retJson = file_get_contents($url);

        $ret = $this->accessTokenJsonDecode($retJson);

        $js_ticket = $ret->{"ticket"};
        $expires_in = $ret->{"expires_in"};
        $ret->{"expires_time"} = time() + $expires_in;

        $js_ticket_file_path = $this->container->getParameter("wechat_js_ticket_file");
        $this->file_force_contents($js_ticket_file_path, json_encode($ret));

        $info = sprintf("js_ticket update: url: \"%s\", token: \"%s\", expires: \"%s\"", $url, $js_ticket, $expires_in);

        return $js_ticket;
    }

    private function toLog($logStr)
    {
        if($this->container->getParameter("app.debug.wechat.access_info") === true){
            $this->logger->info($logStr);
        }
    }

    private function file_force_contents($filename, $data, $flags = 0)
    {
        if(!is_dir(dirname($filename)))
            mkdir(dirname($filename) . '/', 0777, TRUE);

        return file_put_contents($filename, $data, $flags);
    }

    private function accessTokenJsonDecode($content)
    {
        $ret = json_decode($content);

        if ($ret === null && json_last_error() !== JSON_ERROR_NONE) {
          $error_message = sprintf("Failed to parse json string '%s', error: '%s'", $content , json_last_error());
          throw new \LogicException($error_message);
        }

        return $ret;
    }

    private function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0,$max)];
        }

        return $str;
    }
}