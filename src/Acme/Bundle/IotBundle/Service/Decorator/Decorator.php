<?php

namespace Acme\Bundle\IotBundle\Service\Decorator;

use Acme\Bundle\IotBundle\Service\Decorator\IComponent;

abstract class Decorator extends IComponent
{
    public function __construct(IComponent $component)
    {
        $this->component = $component;
        $this->container = $this->component->container;
    }
}