<?php

namespace Acme\Bundle\IotBundle\Service\Facade;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator\BasicQueryComponent;
use Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator\SecurityDecryptDecorator;
use Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator\ParseRequestQueryDecorator;
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
        $component = new BasicQueryComponent($this->container);
        $component = new SecurityDecryptDecorator($component);
        $component = new ParseRequestQueryDecorator($component);
        $component = new SecurityEncryptDecorator($component);

        return $component->process();
    }
}