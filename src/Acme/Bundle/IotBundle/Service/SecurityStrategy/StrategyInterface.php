<?php

namespace Acme\Bundle\IotBundle\Service\SecurityStrategy;

interface StrategyInterface
{
    public function encrypt(Array $dataPack);
    public function decrypt(Array $dataPack);
}