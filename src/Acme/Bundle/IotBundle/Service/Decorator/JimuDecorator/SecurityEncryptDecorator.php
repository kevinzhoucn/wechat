<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;

class SecurityEncryptDecorator extends Decorator
{
    // Remember assign container to class
    public function __construct(IComponent $component)
    {
        $this->component = $component;
        $this->container = $this->component->container;
    }

    public function process()
    {        
        $proess_result = $this->component->process();
        $security = $this->container->get('acme.iot.security');

        return 'result:' . $security->encryptAlert($proess_result);
    }
}