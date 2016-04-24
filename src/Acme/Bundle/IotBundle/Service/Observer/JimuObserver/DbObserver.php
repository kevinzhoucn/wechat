<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;

use Acme\Bundle\IotBundle\Entity\DataPoint;

class DbObserver extends ConcreteObserver
{
    private $deviceArray;
    public function doUpdate()
    {
        $deviceArray = $this->subject->getContext()->buildQueryArray();
        $device = null;
        if($deviceArray) {
            $this->deviceArray = $deviceArray;

            $sn = $this->getValue('sn');
            $device = $this->subject->getContext()->getOrCreateNewDevice($sn);

            if(!$device->getSn()) {
                $device->setSn($sn);

                $model = $this->getValue('model');
                $device->setModel($model);

                $vender = $this->getValue('vender');
                $device->setVender($vender);

                $random = $this->getValue('random');
                $device->setVender($random);               
            }


            $data = $this->getValue('value');
            if($data) {
                $datapoint = new DataPoint();
                $datapoint->setData($data);
                $device->addDatapoint($datapoint);

                // echo 'I will save to DB';

                $this->subject->getContext()->saveItemArrayToDB(array($device, $datapoint));
            }
        }
    }

    private function getValue($strName)
    {
        $deviceArray = $this->deviceArray;
        return isset($deviceArray[$strName]) ? $deviceArray[$strName] : 'empty';
    }
}