<?php

namespace Acme\Bundle\IotBundle\Service\Decorator;
use Symfony\Component\DependencyInjection\ContainerInterface;

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