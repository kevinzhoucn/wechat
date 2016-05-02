<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BasicQueryComponent extends IComponent
{    
    public function process()
    {
        return $this->getRequestQueryString();
    }

    private function getRequestQueryString()
    {
        return $requestStr = $this->container->get('request')->getQueryString();
    }
}