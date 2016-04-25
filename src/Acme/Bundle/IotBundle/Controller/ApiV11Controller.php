<?php

namespace Acme\Bundle\IotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\Bundle\IotBundle\Entity\Device;
use Acme\Bundle\IotBundle\Entity\DataPoint;

class ApiV11Controller extends Controller
{
    public function sendAction(Request $request)
    {
        // $sn = $request->get('sn');

        $query = $request->getQueryString();        
        $logger = $this->container->get("my_service.logger");
        $logger->info("received encrypt data: " . $query);

        $security = $this->container->get('acme.iot.security');
        $decryptStr = $security->decryptAlert($query);
        $logger->info("received decrypt data: " . $decryptStr);

        $queryStr = explode('&', $decryptStr);
        $result = 'result:';
        $result_code = '';

        if($queryStr && strpos($decryptStr, "&") && strpos($decryptStr, "=")) {
            $result_code = $this->getSuccessResult($logger, $queryStr);
        } else {
            $result_code = "1," . time() . ",,";
        }
        
        $encryptStr = $security->encryptAlert($result_code);

        $logger->info("response data: " . $result_code . " encrypt data: " . $encryptStr);
        $logger->info("response decrypt data: " . $security->decryptAlert($encryptStr));

        // $result .= $result_code;
        $result .= $encryptStr;
        
        return new Response($result);
    }

    private function removeSpace($str)
    {
        return strtolower(preg_replace("/\s+|　/", "", urldecode($str)));
    }

    private function checkIfAlert($data)
    {
        $alert = null;
        foreach (explode('_', $data) as $chunk) {
            $param = explode("-", $chunk);

            if($param) {
                $num = $this->removeSpace($param[1]);
                // echo $num;
                if($num === 0 or $num === '0') {
                    $alert = true;
                }
            }
        }

        return $alert;
    }

    private function sendAlert($device)
    {
        $sms = $this->container->get('acme.alert.sms.meilian');
        // echo 'will send alert message! </br>';
        $logger = $this->container->get("my_service.logger");

        $mobiles = $device->getAlertMobiles();
        // $sn = $device

        $logger->info("mobile: " . $mobiles);

        // $mobile = '18001358893';
        $content = '状态码: 0001';

        $result = 'empty';

        if(strlen($mobiles) > 10) {
            // $result = $sms->sendSMSText($mobiles, $content);            
            $logger->info("send message to mobiles: " . $mobiles . ", result: " .$result);
        }

        // echo 'platform give response: ' . $result . '</br>';

        return $result;
    }

    private function getSuccessResult($logger, $queryStr)
    {
        $device = $datapoint = $sn = $model = $vender = $data = $random = $timestamp = null;
        $alert = null;

        $em = $this->getDoctrine()->getManager();

        foreach ($queryStr as $chunk) {
            $param = explode("=", $chunk);

            if ($param) {                
                // printf("Value for parameter \"%s\" is \"%s\"<br/>\n", $this->removeSpace($param[0]), $this->removeSpace($param[1]));
                // echo $this->removeSpace($param[0]) . '</br>';
                switch ($this->removeSpace($param[0])) {
                    case 'sn':
                        $sn = $this->removeSpace($param[1]);
                        // echo $sn . '</br>';
                        break;
                    case 'model':
                        $model = $this->removeSpace($param[1]);
                        // echo $model . '</br>';
                        break;
                    case 'vender':
                        $vender = $this->removeSpace($param[1]);
                        // echo $vender . '</br>';
                        break;
                    case 'random':
                        $random = $this->removeSpace($param[1]);
                        // echo $random . '</br>';
                        break;
                    case 'value':
                        $data = $this->removeSpace($param[1]);
                        $alert = $this->checkIfAlert($data);
                        // echo $data . '</br>';
                        // $timestamp = time();
                        break;
                }
            }            
        }

        $device = $em->getRepository('AcmeIotBundle:Device')->findOneBy(array('sn' => $sn));

        if(!$device) {
            $device = new Device();
            $device->setName($sn);
            $device->setSn($sn);
            $device->setModel($model);
            $device->setVender($vender);
        }

        if($data) {
            $datapoint = new DataPoint();
            $datapoint->setData($data);
            $device->addDatapoint($datapoint);            
        }

        if($device && $datapoint) {
            if($alert) {
                $logger->info("trigger alert!");
                // if(true) {
                if($device->getNextAlertTime() < time()) {
                    $this->sendAlert($device);
                    $device->setNextAlertTime(time() + 300);
                    $logger->info("set alert " . $device->getNextAlertTime());
                } else {
                    $logger->info("time now: " . time() . "get alert " . $device->getNextAlertTime());
                }
            }

            $em->persist($datapoint);
            $em->persist($device);

            $em->flush();
        }

        $result_code = "0," . time() . "," . $random;

        return $result_code;
    }
}
