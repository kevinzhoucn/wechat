<?php

namespace Acme\Bundle\IotBundle\Service\SecurityFactory;

abstract class AbstractCreator
{
    protected abstract function encryptMethod();
    protected abstract function decryptMethod();

    public function startEncrypt()
    {
        $mfg = $this->encryptMethod();
        return $mfg;
    }

    public function startDecrypt()
    {
        $mfg = $this->decryptMethod();
        return $mfg;
    }
}