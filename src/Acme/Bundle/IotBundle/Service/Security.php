<?php

namespace Acme\Bundle\IotBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
// use Acme\Bundle\IotBundle\Service\XXTEA\Xxtea;
// use Acme\Bundle\IotBundle\Service\SecurityFactory\XxteaFactory;

class Security
{
    private $container;
    private $method;
    private $logger;
    private $securityFactory;
    private $securityStrategy;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->init();        
    }

    private function init()
    {
        if($this->container->hasParameter('app.iot.security.method')) {
            $this->method = $this->container->getParameter('app.iot.security.method');

            switch (strtolower($this->method)) {
                case 'xxtea':
                    $this->securityStrategy = $this->container->get('acme.iot.security.xxtea');
                    break;
                
                default:
                    $this->securityStrategy = $this->container->get('acme.iot.security.xxtea');
                    break;
            }
        } else {
            $this->securityStrategy = $this->container->get('acme.iot.security.xxtea');
        }
    }

    public function encrypt(Array $dataPack)    
    {
        return $this->securityStrategy->encrypt($dataPack);
    }

    public function decrypt(Array $dataPack)
    {
        return $this->securityStrategy->decrypt($dataPack);
    }
}