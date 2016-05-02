<?php

namespace Acme\Bundle\IotBundle\Service\Decorator;

abstract class IComponent
{
    protected $component;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract public function process();
}