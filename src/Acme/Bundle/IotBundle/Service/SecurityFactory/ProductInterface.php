<?php

namespace Acme\Bundle\IotBundle\Service\SecurityFactory;

interface ProductInterface
{
    public $str;
    public $key;

    public function encryptMethod();
    public function decryptMethod();
}