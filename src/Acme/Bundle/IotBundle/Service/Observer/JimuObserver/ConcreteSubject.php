<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;

class ConcreteSubject implements \SplSubject
{
    private $observers;
    private $context;

    public function __construct() {
        $this->observers = new \SplObjectStorage();
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

    public function setContext($context)
    {
        $this->context = $context;
    }
}