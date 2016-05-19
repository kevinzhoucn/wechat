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
    private $queryString;
    private $decryptQueryString;
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->init();
    }

    private function init()
    {
        $this->entityManager = $this->container->get('doctrine')->getManager();
        // $this->queryString = $this->container->get('request')->getQueryString();
        $this->logger = $this->container->get("my_service.logger");
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function setDecryptQueryString($decryptQueryString)
    {
        $this->decryptQueryString = $decryptQueryString;
    }

    public function getDecryptQueryString()
    {
        return $this->decryptQueryString;
    }

    public function buildQueryArray($queryStr = null)
    {
        $tempArray = array();
        $queryString = null;

        if($queryString) {
            $queryString = $queryStr;
        } else {
            if($this->decryptQueryString) {
                $queryString = $this->decryptQueryString;
            }
        }

        if($queryString && strpos($queryString, "&") && strpos($queryString, "=")) {
            $tempArray = explode('&', $queryString);
            foreach ($tempArray as $chunk) {
                $param = explode("=", $chunk);
                if ($param) {
                    $tempArray[$param[0]] = $param[1];
                }
            }
        }

        return $tempArray;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getLogger()
    {
        return $this->logger ? $this->logger : $this->container->get("my_service.logger");
    }

    public function getOrCreateNewDevice($sn)
    {
        if(!$sn) return null;
        
        $device = $this->entityManager->getRepository('AcmeIotBundle:Device')->findOneBy(array('sn' => $sn));
        if(!$device){
            $device = new Device();
        }

        return $device;
    }

    public function getAlertRuleBySn($sn)
    {
        if(!$sn) return null;

        $alertRule = $this->entityManager->getRepository('AcmeAlertBundle:AlertRule')->SearchSnAlertRuleDescById($sn);

        if($alertRule) {
            $this->getLogger()->info(sprintf("Get alert rule: %s", $alertRule->getId()));
        }
        return $alertRule;
    }

    public function saveItemToDB($item)
    {
        if($item) {
            $this->entityManager->persist($item);

            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }

    public function saveItemArrayToDB($items = array())
    {
        if(count($items) > 0) {
            foreach ($items as $item) {
                $this->entityManager->persist($item);
            }
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }

    public function getSMSService()
    {
        return $this->container->get('acme.alert.sms.meilian');
    }

    public function getWechatService()
    {
        return $this->container->get('acme.wechat.api');
    }
}