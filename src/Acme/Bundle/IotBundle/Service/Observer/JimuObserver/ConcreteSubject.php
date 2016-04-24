<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;
use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\ConcreteContext;

class ConcreteSubject implements \SplSubject
{
    private $observers;
    private $context;

    public function __construct(ConcreteContext $context) {
        $this->observers = new \SplObjectStorage();
        $this->context = $context;
    }

    public function attach(\SplObserver $observer) {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer) {
        $this->observers->detach($observer);
    }

    public function notify() {
        foreach ($this->observers as $obs) {
            $obs->update($this);
        }
    }

    public function getContext()
    {
        return $this->context;
    }

    // public function setContext($context)
    // {
    //     $this->context = $context;
    // }
}