<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;

abstract class ConcreteObserver implements \SplObserver
{
    private $subject;
    public function __construct(\SplSubject $subject)
    {
        $this->subject = $subject;
        $this->subject->attach($this);
    }

    public function update(\SplSubject $subject)
    {
        if($subject === $this->subject){
            $this->doUpdate();
        }        
    }

    abstract function doUpdate();
}