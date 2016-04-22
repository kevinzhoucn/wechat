<?php

namespace Acme\Bundle\IotBundle\Service\SecurityStrategy;

use Acme\Bundle\IotBundle\Service\XXTEA\Xxtea;

class XxteaStrategy implements StrategyInterface
{
    public function encrypt(Array $dataPack)
    {
        $str = $dataPack['str'];
        $key = $dataPack['key'];
        return Xxtea::encrypt($str, $key);
    }

    public function decrypt(Array $dataPack)
    {
        $str = $dataPack['str'];
        $key = $dataPack['key'];
        return Xxtea::decrypt($str, $key);
    }
}