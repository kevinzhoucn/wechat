<?php

namespace Acme\Bundle\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcmeWebBundle:Front:index.html.twig');
    }
}
