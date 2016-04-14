<?php

namespace Acme\Bundle\IotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiBaseController extends Controller
{
    public function checkdateAction()
    {
        $now = time();
        return new Response($now);
    }

    public function checkSecurityAction()
    {
        // $sec_str = $this->container->get('acme.iot.security')->encrypt('str', '12');
        $security = $this->container->get('acme.iot.security');
        $str_pack = array('str' => '595c09e211b549902a09cec2e1fe7bfdc6d2ca285c3e531295d1efba1040c5ef574bca135ff603115b8c089f',
                          'key' => 'TTuuIvb76TY123Ki');
        $sec_str = $security->decrypt($str_pack);
        return new Response($sec_str);
    }
}
