<?php

namespace Acme\Bundle\WechatBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class WeChatApi
{
    private $container;
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $this->container->get("my_service.logger");
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
        if($this->container->hasParameter("wechat_access_token") && $this->container->hasParameter("wechat_access_token_expires_in")){
            $expires_in = $this->container->getParameter("wechat_access_token_expires_in");
            // 
            $now = time();
            if($expires_in > $now){
                $info = sprintf("access: expires: \"%s\"", $expires_in);
                $access_token = $this->updateAccessToken();
            } else {
                $access_token = $this->container->getParameter("wechat_access_token");
                $info = sprintf("access: in 2 hours token: \"%s\"", $access_token);
            }
        } else {
            $info = sprintf("access: no token");
            $access_token = $this->updateAccessToken();
        }

        $this->toLog($info);
        return $access_token;
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

        $ret = json_decode($retJson);

        if ($ret === null && json_last_error() !== JSON_ERROR_NONE) {
          $error_message = sprintf("Failed to parse json string '%s', error: '%s'", $retJson , json_last_error());
          throw new \LogicException($error_message);
        }

        $access_token = $ret->{"access_token"};
        $expires_in = $ret->{"expires_in"};

        // $this->container->setParameter("wechat_access_token", $access_token);
        // $this->container->setParameter("wechat_access_token_expires_in", (int)$access_token + $expires_time_window);

        $info = sprintf("access token update: url: \"%s\", token: \"%s\", expires: \"%s\"", $url, $access_token, $expires_in);

        return $access_token;
    }

    private function toLog($logStr)
    {
        if($this->container->getParameter("app.debug.wechat.access_info") === true){            
            $this->logger->info($logStr);
        }
    }
}