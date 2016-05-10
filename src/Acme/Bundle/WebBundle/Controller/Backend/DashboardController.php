<?php

/*
 * This file is DashboardController
 */

namespace Acme\Bundle\WebBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *Backend dashboard controller.
 *
 * @author kevin.zhou <kevin.zhou@hotmail.co.uk>
 */

class DashboardController extends Controller
{
    /**
     * Backend dashboard display action.
     */
    public function mainAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AcmeUserBundle:User')->findAll();

        return $this->render('AcmeWebBundle:Backend/Dashboard:main.html.twig', 
                            array(
                                'users' => $users)
                            );
    }
}