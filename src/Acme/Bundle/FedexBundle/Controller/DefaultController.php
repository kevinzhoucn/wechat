<?php

namespace Acme\Bundle\FedexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcmeFedexBundle:Default:index.html.twig');
    }
}
