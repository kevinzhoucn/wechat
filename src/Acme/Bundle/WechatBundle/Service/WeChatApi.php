<?php

namespace Acme\Bundle\WechatBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class WeChatApi
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
            $result = $echoStr;
        }

        if($this->container->getParameter("app.debug.wechat.access_info") === true){
            $logger = $this->container->get("my_service.logger");
            $info = sprintf("signature: \"%s\", timestamp: \"%s\", nonce: \"%s\", echostr: \"%s\"", $signature, $timestamp, $nonce, $echoStr);
            $logger->info($info);
        }

        return $result;
    }
}