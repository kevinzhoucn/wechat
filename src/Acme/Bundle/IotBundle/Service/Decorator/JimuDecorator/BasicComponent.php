<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BasicComponent extends IComponent
{
    public function process()
    {        
        return "1," . time() . "random1234567";
    }
}