<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SecurityEncryptDecorator extends Decorator
{
    public function __construct(IComponent $component, ContainerInterface $container)
    {
        $this->component = $component;
        parent::__construct($container);
    }

    public function process()
    {        
        $proess_result = $this->component->process();
        $security = $this->container->get('acme.iot.security');

        return $security->encryptAlert($proess_result);
    }
}