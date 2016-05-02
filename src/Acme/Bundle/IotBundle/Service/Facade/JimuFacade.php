<?php

namespace Acme\Bundle\IotBundle\Service\Facade;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator\BasicComponent;
use Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator\SecurityEncryptDecorator;

class JimuFacade
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function handleDeviceRequest()
    {
        $request = $this->container->get('request');
        echo $request;

        $component = new BasicComponent($this->container);
        $component = new SecurityEncryptDecorator($component, $this->container);

        return $component->process();
    }
}