<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\Bundle\IotBundle\Entity\Device;
use Acme\Bundle\IotBundle\Entity\DataPoint;

class ConcreteContext
{
    private $container;

    // Use traits or compositer
    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->init();
    }

    private function init()
    {
        $this->entityManager = $this->container->getDoctrine()->getManager();
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getOrCreateNewDevice($sn)
    {
        $device = $em->getRepository('AcmeIotBundle:Device')->findOneBy(array('sn' => $sn));
        if(!device){
            $device = new Device();
        }

        return $device;
    }

    public function saveItemToDB($item)
    {
        if($item) {
            $this->entityManager->persist($datapoint);

            $em->flush();
            return true;
        } else {
            return false;
        }
    }
}