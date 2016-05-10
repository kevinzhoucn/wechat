<?php

namespace Acme\Bundle\IotBundle\Service\Decorator\JimuDecorator;

use Acme\Bundle\IotBundle\Service\Decorator\Decorator;
use Acme\Bundle\IotBundle\Service\Decorator\IComponent;
use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\ConcreteContext;
use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\ConcreteSubject;
use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\DbObserver;
use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\AlertObserver;

class ParseRequestQueryDecorator extends Decorator
{
    private $subject;
    // Remember assign container to class
    public function __construct(IComponent $component)
    {
        parent::__construct($component);        
        $context = new ConcreteContext($this->container);
        $this->subject = new ConcreteSubject($context);        
    }

    public function process()
    {
        $query_str_array = $result = null;
        $query_str = $this->component->process();

        if(strpos($query_str, "&") && strpos($query_str, "=")) {
            $query_str_array = explode('&', $query_str);

            $dbObserver = new DbObserver($this->subject);
            $alertObserver = new AlertObserver($this->subject);
            $this->subject->getContext()->setDecryptQueryString($query_str);
            $this->subject->notify();
        }

        if($query_str_array) {
            $result = sprintf("0,%s,,", time());
        } else {
            $result = sprintf("1,%s,,", time());
        }

        return $result;
    }
}