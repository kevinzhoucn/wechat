<?php

namespace Acme\Bundle\WechatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WechatController extends Controller
{
    public function checksignatureAction(Request $request)
    {
        $checkSignature = $this->container->get('acme.wechat.api');
        $result = $checkSignature->checkSignatureValid($request);

        return new Response($result);
    }

    public function getAccessTokenAction()
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        $result = $webchatApi->getAccessToken();

        return new Response($result);
    }

    public function getJsTicketAction()
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        $result = $webchatApi->getJsTicket();

        return new Response($result);
    }

    public function airkissAction()
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        list($appid, $timestamp, $nonceStr, $signature) = $webchatApi->getJsTicketSignatureList();

        // return new Response(sprintf("appid: %s, timestamp: %s, nonce: %s, signature: %s", $appid, $timestamp, $nonceStr, $signature));
        return $this->render('AcmeWechatBundle:Wechat:airkiss.html.twig', array('appid' => $appid, 'timestamp' => $timestamp, 'nonceStr' => $nonceStr, 'signature' => $signature));
    }
}
