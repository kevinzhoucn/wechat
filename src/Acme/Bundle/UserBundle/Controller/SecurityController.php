<?php

namespace Acme\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                    'AcmeWebBundle:Security/User:login.html.twig',
                    array(
                        // last username entered by the user
                        'last_username' => $lastUsername,
                        'error'         => $error,
                    )
                );
    }
}