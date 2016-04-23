<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;

class ParseRequestQueryDecorator extends Decorator
{
    // Remember assign container to class
    public function __construct(IComponent $component)
    {
        $this->component = $component;
        $this->container = $this->component->container;
    }

    public function process()
    {
        $query_str_array = $result = null;
        $query_str = $this->component->process();

        if(strpos($query_str, "&") && strpos($query_str, "=")) {
            $query_str_array = explode('&', $query_str);
        }

        if($query_str_array) {
            $result = sprintf("0,%s,,", time());
        } else {
            $result = sprintf("1,%s,,", time());
        }

        return $result;
    }
}