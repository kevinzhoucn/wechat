<?php

namespace Acme\Bundle\AlertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AlertController extends Controller
{
    public function indexAction()
    {        
        return $this->render('AcmeAlertBundle:Alert:index.html.twig');
    }

    // public function smsAction()
    // {
    //     $sms = $this->container->get('acme.alert.sms.meilian');

    //     $result = $sms->sendSMSText('18001358893', '欢迎使用 验证码为1234！');

    //     return new Response($result);
    // }
}
