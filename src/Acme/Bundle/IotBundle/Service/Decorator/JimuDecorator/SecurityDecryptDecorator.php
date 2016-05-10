<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;

class SecurityDecryptDecorator extends Decorator
{
    public function process()
    {
        $security = $this->container->get('acme.iot.security');
        $proess_result = $this->component->process();
        return $security->decryptAlert($proess_result);
    }
}