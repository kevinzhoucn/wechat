<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;

use Acme\Bundle\IotBundle\Entity\DataPoint;

class DbObserver extends ConcreteObserver
{
    private $deviceArray;
    private $randomStr;
    private $correctFlag;

    public function doUpdate()
    {
        $deviceArray = $this->subject->getContext()->buildQueryArray();
        $device = null;
        if($deviceArray) {
            $this->deviceArray = $deviceArray;

            $sn = $this->getValue('sn');
            $device = $this->subject->getContext()->getOrCreateNewDevice($sn);

            $random = $this->getValue('random');
            $this->randomStr = $random;

            if(!$device->getSn()) {
                $device->setSn($sn);

                $model = $this->getValue('model');
                $device->setModel($model);

                $vender = $this->getValue('vender');
                $device->setVender($vender);                
                // $device->setRandom($random);               
            }


            $data = $this->getValue('value');
            if($data) {
                $datapoint = new DataPoint();
                $datapoint->setData($data);
                $device->addDatapoint($datapoint);

                // echo 'I will save to DB';

                $this->subject->getContext()->saveItemArrayToDB(array($device, $datapoint));
                $this->correctFlag = true;
            }
        }
    }

    private function getValue($strName)
    {
        $deviceArray = $this->deviceArray;
        return isset($deviceArray[$strName]) ? $deviceArray[$strName] : 'empty';
    }

    public function getResultStr()
    {
        $retResult = null;
        if($this->randomStr) {
            if($this->correctFlag) {
                $retResult = sprintf("0,%s,%s,", time(), $this->randomStr);
            } else {
                $retResult = sprintf("1,%s,%s,", time(), $this->randomStr);
            }
        } else {
            $retResult = sprintf("1,%s,,", time());
        }

        return $retResult;
    }
}