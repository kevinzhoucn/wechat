<?php

namespace Acme\Bundle\IotBundle\Service\SecurityFactory;

use Acme\Bundle\IotBundle\Service\XXTEA\Xxtea;

class XxteaProduct implements ProductInterface
{
    private $mfgProduct;

    public function encryptMethod()
    {
        Xxtea::encrypt($this->str, $this->key);
    }

    public function decryptMethod()
    {
        Xxtea::decrypt($this->str, $this->key);
    }
}