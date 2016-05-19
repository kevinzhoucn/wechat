<?php

namespace Acme\Bundle\IotBundle\Service\Observer\JimuObserver;

use Acme\Bundle\IotBundle\Entity\DataPoint;

class AlertObserver extends ConcreteObserver
{
    private $deviceArray;
    public function doUpdate()
    {
        $deviceArray = $this->subject->getContext()->buildQueryArray();
        $device = null;
        if($deviceArray) {
            $this->deviceArray = $deviceArray;

            $sn = $this->getValue('sn');
            $alertRule = $this->subject->getContext()->getAlertRuleBySn($sn);

            if($alertRule) {
                $device = $alertRule->getDevice();
            }            
            $alert = $content = null;

            if($alertRule && $device) {
                $channelRule = $alertRule->getAlertRule();
                $informRule = $alertRule->getInformRule();

                $tempRules = explode('||', $channelRule);
                foreach ($tempRules as $chunk) {
                    $tempSingleRule = explode(':', $chunk);
                    $this->subject->getContext()->getLogger()->info(sprintf('Check if Alert: single rule, channle: %s, value: %s', $tempSingleRule[0], $tempSingleRule[1]));
                    if($tempSingleRule[0] === '0') {
                        if($tempSingleRule[1] === 'Y') {
                            $alert = true;
                            $content .= '0.报警信号！';
                        }
                    }
                    if($tempSingleRule[0] === '1') {
                        if($tempSingleRule[1] === 'Y') {
                            $alert = true;
                            $content .= '1.测试信号！';
                        }
                    }
                }

                if($alert) {
                    $this->subject->getContext()->getLogger()->info("trigger alert!");
                    if(!$device->getNextAlertTime() || $device->getNextAlertTime() < time()) {
                        $this->sendAlert($device, $informRule, $content);
                        $device->setNextAlertTime(time() + 300);
                        $this->subject->getContext()->getLogger()->info("set alert " . $device->getNextAlertTime());
                        $this->subject->getContext()->saveItemToDB($device);
                    } else {
                        $this->subject->getContext()->getLogger()->info("time now: " . time() . "get alert " . $device->getNextAlertTime());
                    }
                }
            }            
        }
    }

    private function getValue($strName)
    {
        $deviceArray = $this->deviceArray;
        return isset($deviceArray[$strName]) ? $deviceArray[$strName] : 'empty';
    }

    private function sendAlert($device, $informRule, $content)
    {
        $sms = $this->subject->getContext()->getSMSService();
        $wechat = $this->subject->getContext()->getWechatService();
        $logger = $this->subject->getContext()->getLogger();

        $mobiles = $device->getAlertMobiles();
        $user = $device->getUser();
        $logger->info("mobile: " . $mobiles);

        $result = 'empty';

        if(strlen($mobiles) > 10) {
            if($informRule) {
                $tempRules = explode('||', $informRule);

                foreach ($tempRules as $chunk) {
                    $tempSingleRule = explode(':', $chunk);
                    $logger->info(sprintf('Check inform rule: single rule, method: %s, value: %s', $tempSingleRule[0], $tempSingleRule[1]));
                    if($tempSingleRule[0] === 'sms') {
                        if($tempSingleRule[1] === 'Y') {
                            $logger->info('Got alert: send sms');
                            $result .= $sms->sendSMSText($mobiles, $content);
                        }
                    }
                    if($tempSingleRule[0] === 'voice') {
                        if($tempSingleRule[1] === 'Y') {
                            $logger->info('Got alert: send voice');
                            $result .= $sms->sendSMSVoice($mobiles, $content);
                        }
                    }
                    if($tempSingleRule[0] === 'wechat') {
                        if($tempSingleRule[1] === 'Y') {
                            $logger->info('Got alert: send wechat message');
                            // $result .= $sms->sendSMSVoice($mobiles, $content);
                            $wechat->sendWechatTemplate($user->getUsername(), $device->getSn());
                        }
                    }
                }
            }

            $logger->info("send message to mobiles: " . $mobiles . ", result: " .$result);
        }
        return $result;
    }
}