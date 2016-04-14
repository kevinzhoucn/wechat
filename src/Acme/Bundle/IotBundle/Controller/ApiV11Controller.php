<?php

namespace Acme\Bundle\IotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiV11Controller extends Controller
{
    public function sendAction(Request $request)
    {
        // $sn = $request->get('sn');

        $query = $request->getQueryString();

        foreach (explode('&', $query) as $chunk) {
            $param = explode("=", $chunk);

            if ($param) {
                printf("Value for parameter \"%s\" is \"%s\"<br/>\n", $this->removeSpace($param[0]), $this->removeSpace($param[1]));
            }
        }
        
        return new Response($query);
    }

    private function removeSpace($str)
    {
        return preg_replace("/\s+|　/", "", urldecode($str));
    }
}
