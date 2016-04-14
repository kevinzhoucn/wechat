<?php

namespace Acme\Bundle\IotBundle\Service\SecurityFactory;

class XxteaFactory extends AbstractCreator
{
    protected function encryptMethod()
    {
        $xxtea = new XxteaProduct();
        return $xxtea->encryptMethod();
    }

    protected function decryptMethod()
    {
        $xxtea = new XxteaProduct();
        return $xxtea->decryptMethod();
    }
}