<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;

class DbObserver extends ConcreteObserver
{
    public function doUpdate()
    {
        printf("\nupdate from observer!");
    }
}