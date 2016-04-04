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
}
