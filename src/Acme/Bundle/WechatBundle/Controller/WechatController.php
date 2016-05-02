<?php

namespace Acme\Bundle\WechatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\Bundle\WechatBundle\Form\BindDeviceType;
use Acme\Bundle\WechatBundle\Form\AlertSetType;
use Acme\Bundle\UserBundle\Entity\User;
use Acme\Bundle\IotBundle\Entity\Device;
use Acme\Bundle\AlertBundle\Entity\AlertRule;

class WechatController extends Controller
{
    public function checksignatureAction(Request $request)
    {
        $checkSignature = $this->container->get('acme.wechat.api');
        $result = $checkSignature->checkSignatureValid($request);

        return new Response($result);
    }

    public function getAccessTokenAction()
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        $result = $webchatApi->getAccessToken();

        return new Response($result);
    }

    public function getJsTicketAction()
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        $result = $webchatApi->getJsTicket();

        return new Response($result);
    }

    public function airkissAction(Request $request)
    {
        $url = $request->getUri();
        $wechatParams = $this->getWechatParams($url);
        // return new Response(sprintf("appid: %s, timestamp: %s, nonce: %s, signature: %s", $appid, $timestamp, $nonceStr, $signature));
        return $this->render('AcmeWebBundle:Wechat:airkiss.html.twig', array('wechat' => $wechatParams));
    }
    
    /**
     * Bind a new Device to User.
     *
     */
    public function bindAction(Request $request)
    {
        $openid = $this->getOpenid($request);

        // $openid = "od8M9wBOnFNrtkp9oJw3PgiVdT_I";
        // $session = $request->getSession()->set("user_openid", $openid);

        $data = array();
        $form = $this->createForm(new BindDeviceType(), $data);
        $form->handleRequest($request);

        $logger = $this->container->get("my_service.logger");

        if ($form->isSubmitted() && $form->isValid()) {
            $bind_device = $form->getData();

            $username = $openid;

            $phone = $this->removeSpace($bind_device['phone']);
            $device_sn = $this->removeSpace($bind_device['sn']);

            $phone1 = $this->removeSpace($bind_device['phone1']);
            $phone2 = $this->removeSpace($bind_device['phone2']);
            
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AcmeUserBundle:User')
                       ->findOneBy(array('username' => $username));

            // echo implode(',', $user->getPhones()->toArray());
            // die;

            $device = $em->getRepository('AcmeIotBundle:Device')
                         ->findOneBy(array('sn' => $device_sn));

            if(!$user) {
                $user = new User();
                $user->setUsername($username);
                // $user->addPhone($username);
            }

            $phones = array($phone);

            if($phone1 && strlen($phone1) === 11) {
                if(!in_array($phone1, $phones)) {
                    $phones[] = $phone1;
                }
            }

            if($phone2 && strlen($phone2) === 11) {
                // $user->addPhone($phone2);
                if(!in_array($phone2, $phones)) {
                    $phones[] = $phone2;
                }
            }

            $user->setPhones($phones);

            if(!$device) {
                $device = new Device();
                $device->setSn($device_sn);
            }

            $user->addDevice($device);
            $device->setUser($user);

            $em->persist($device);
            $em->persist($user);
            $em->flush();

            $info = 'Success: ' . $user->getPhoneString();
            // $info .= 'User devices total: ' . count($user->getDevices());
            // $info .= 'User phones total: ' . count($user->getPhones());
            
            $logger->info($info);

            // return $this->redirectToRoute('device_show', array('id' => $device->getId()));            

            $deviceShowUrl = $this->generateUrl('acme_wechat_device_show', array('sn' => $device->getSn()));
            return $this->redirect($deviceShowUrl);
            // return $this->redirectToRoute('device_bind_success');
        }

        return $this->render('AcmeWebBundle:Wechat:bind.html.twig', array(
                             'device' => $data,
                             'form' => $form->createView(),
                            ));
    }

    // public function bindSuccessAction( $sn, $phones )
    public function bindSuccessAction(Request $request)
    {
        $wechatParams = $this->getWechatParams($request->getUri());
        // $userPhone = array_shift($phones);
        // $otherPhones = null;

        // if( $phones ) {
        //     $otherPhones = implode(',', $phones);
        // }

        // return $this->render('AcmeWebBundle:Wechat:bind_success.html.twig', 
        //                      array('sn' => $sn, 'userPhone' => $userPhone, 'otherPhones' => $otherPhones)
        //                     );
        return $this->render('AcmeWebBundle:Wechat:bind_success.html.twig', array('wechat' => $wechatParams));
    }

    public function userbindAction(Request $request)
    {
        $logger = $this->container->get("my_service.logger");
        $redirect_url = $request->getSchemeAndHttpHost() . $this->generateUrl('device_bind_new');

        $session = $request->getSession();
        $openid = $session->get('user_openid');

        $logger->info('user bind action: openid from sessoin: ' . !$openid ? 'NULL' : $openid);

        if( !$openid ) {
            $wechat_auth_url = $this->container->getParameter('wechat_auth_url');
            $wechat_appid = $this->container->getParameter('wechat_appid');            

            $scope = $this->container->getParameter('wechat_auth_scope_base');

            $wechat_auth_url = sprintf($wechat_auth_url, $wechat_appid, urlencode($redirect_url), $scope, '1234');

            $logger->info('user bind action: build auth url: ' . $wechat_auth_url);
            $logger->info('user bind action: will redirect to it!');
            
            // $wechat_auth_url = sprintf($wechat_auth_url, $wechat_appid, $redirect_url, 'snsapi_userinfo', '1234');

            return $this->redirect($wechat_auth_url);
        }

        $logger->info('user bind action: get openid from session: ' . $openid);
        $logger->info('user bind action: will redirect to : ' . $redirect_url);

        return $this->redirect($redirect_url);
    }

    public function mydeviceAction(Request $request)
    {
        $logger = $this->container->get("my_service.logger"); 
        $redirect_url = $request->getSchemeAndHttpHost() . '/wechat/api/device/list/';

        $session = $request->getSession();
        $openid = $session->get('user_openid');

        $logger->info('my device action: openid from sessoin: ' . ( !$openid ? 'NULL' : $openid) );

        if( !$openid ) {
            $wechat_auth_url = $this->container->getParameter('wechat_auth_url');
            $wechat_appid = $this->container->getParameter('wechat_appid');            

            $scope = $this->container->getParameter('wechat_auth_scope_base');

            $wechat_auth_url = sprintf($wechat_auth_url, $wechat_appid, urlencode($redirect_url), $scope, '1234');

            $logger->info('my device action: build auth url: ' . $wechat_auth_url);
            $logger->info('my device action: will redirect to it!');
            
            // $wechat_auth_url = sprintf($wechat_auth_url, $wechat_appid, $redirect_url, 'snsapi_userinfo', '1234');

            return $this->redirect($wechat_auth_url);
        }

        $logger->info('my device action: get openid from session: ' . $openid);
        $logger->info('my device action: will redirect to : ' . $redirect_url);

        return $this->redirect($redirect_url);
        // return new Response($wechat_auth_url);
    }

    public function devlistAction(Request $request)
    {
        $logger = $this->container->get("my_service.logger"); 

        $openid = $this->getOpenid($request);

        // $openid = "od8M9wBOnFNrtkp9oJw3PgiVdT_I";
        // $session = $request->getSession()->set("user_openid", $openid);

        $logger->info('Devlist action: get openid: ' . ( !$openid ? 'NULL' : $openid) );

        if( $openid ) {
            $logger->info('Devlist action: response list page!');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AcmeUserBundle:User')
                       ->findOneBy(array('username' => $openid));

            $devices = array();

            if($user) {
                $devices = $user->getDevices();
            }

            return $this->render('AcmeWebBundle:Wechat:devlist.html.twig', 
                                 array('devices' => $devices)
                                );

            // return new Response('Get list page!');
        } else {
        // $response = file_get_contents($wechat_auth_access_token_url);
            $logger->info('Devlist action Error: response null openid!');

            return $this->render('AcmeWebBundle:Wechat:devnull.html.twig');
            // return new Response('Error: Openid is null!');
        }
        // return $this->redirect($wechat_auth_access_token_url);
    }

    public function showDevAction(Request $request, $sn)
    {
        $username = $request->getSession()->get("user_openid");

        // $em = $this->getDoctrine()->getManager();
        // $user = $em->getRepository('AcmeUserBundle:User')
        //            ->findOneBy(array('username' => $openid));

        // // $user_id = $user->getId();
        // // $device = $em->getRepository('AcmeIotBundle:Device')
        // //              ->findOneBy(array('user_id' => $user_id, 'sn' => $sn));
        // $device = $user->getDevices()->findOneBy(array('sn' => $sn));

        $alertItem = $this->getDoctrine()
                       ->getRepository('AcmeAlertBundle:AlertRule')
                       ->findOneBySnJoinedToUser($sn, $username);

        $device = $this->getDoctrine()
                       ->getRepository('AcmeIotBundle:Device')
                       ->findOneBySnJoinedToUser($sn, $username);

        $user = $this->getDoctrine()
                     ->getRepository('AcmeUserBundle:User')
                     ->findOneBy(array('username' => $username));

        $data = array();
        $dataAlertRule = array();
        $dataAlertInformRule = array();
        
        if(!$alertItem) {
            $alertItem = new AlertRule();
            $alertItem->setUser($user);
            $alertItem->setDevice($device);
            $data = array('channel1' => false, 'channel2' => false, 'channel3' => false, 'wechat' => false, 'sms' => false, 'voice' => false);
        } else {
            $alertItemRule = $alertItem->getAlertRule();
            if(!($alertItemRule && strpos($alertItemRule, ":") && strpos($alertItemRule, "||"))) {
                $dataAlertRule = array('channel1' => false, 'channel2' => false, 'channel3' => false);    
            } else {
                $tempRules = explode('||', $alertItemRule);
                foreach ($tempRules as $chunk) {
                    $tempSingleRule = explode(':', $chunk);
                    if($tempSingleRule[0] === '0') {
                        if($tempSingleRule[1] === 'Y') {
                            $dataAlertRule['channel1'] = true;
                        } else {
                            $dataAlertRule['channel1'] = false;
                        }
                    }

                    if($tempSingleRule[0] === '1') {
                        if($tempSingleRule[1] === 'Y') {
                            $dataAlertRule['channel2'] = true;
                        } else {
                            $dataAlertRule['channel2'] = false;
                        }
                    }

                    if($tempSingleRule[0] === '10') {
                        if($tempSingleRule[1] === 'Y') {
                            $dataAlertRule['channel3'] = true;
                        } else {
                            $dataAlertRule['channel3'] = false;
                        }
                    }
                }
            }

            $alertItemInformRule = $alertItem->getInformRule();
            if(!($alertItemInformRule && strpos($alertItemInformRule, ":") && strpos($alertItemInformRule, "||"))) {
                $dataAlertInformRule = array('wechat' => false, 'sms' => false, 'voice' => false);    
            } else {
                $tempRules = explode('||', $alertItemInformRule);
                foreach ($tempRules as $chunk) {
                    $tempSingleRule = explode(':', $chunk);
                    if($tempSingleRule[0] === 'wechat') {
                        if($tempSingleRule[1] === 'Y') {
                            $dataAlertInformRule['wechat'] = true;
                        } else {
                            $dataAlertInformRule['wechat'] = false;
                        }
                    }

                    if($tempSingleRule[0] === 'sms') {
                        if($tempSingleRule[1] === 'Y') {
                            $dataAlertInformRule['sms'] = true;
                        } else {
                            $dataAlertInformRule['sms'] = false;
                        }
                    }

                    if($tempSingleRule[0] === 'voice') {
                        if($tempSingleRule[1] === 'Y') {
                            $dataAlertInformRule['voice'] = true;
                        } else {
                            $dataAlertInformRule['voice'] = false;
                        }
                    }
                }
            }
            $data = array_merge($dataAlertRule, $dataAlertInformRule);
        }

        // var_dump($data);
        // die;
        
        $alertForm = $this->createForm(new AlertSetType(), $data);
        $alertForm->handleRequest($request);

        if ($alertForm->isSubmitted() && $alertForm->isValid()) {
            $alertData = $alertForm->getData();

            // $username = $openid;
            $alertRule = '';
            $channel1 = $alertData['channel1'];
            if($channel1) {
                $alertRule = $alertRule . '0:Y';
            } else {
                $alertRule = $alertRule . '0:N';
            }
            $alertRule = $alertRule . '||';

            $channel2 = $alertData['channel2'];
            if($channel2) {
                $alertRule = $alertRule . '1:Y';
            } else {
                $alertRule = $alertRule . '1:N';
            }
            $alertRule = $alertRule . '||';

            $channel3 = $alertData['channel3'];
            if($channel3) {
                $alertRule = $alertRule . '10:Y';
            } else {
                $alertRule = $alertRule . '10:N';
            }

            $informRule = '';
            $wechat = $alertData['wechat'];
            if($wechat) {
                $informRule = $informRule . 'wechat:Y';
            } else {
                $informRule = $informRule . 'wechat:N';
            }
            $informRule = $informRule . '||';

            $sms = $alertData['sms'];
            if($sms) {
                $informRule = $informRule . 'sms:Y';
            } else {
                $informRule = $informRule . 'sms:N';
            }
            $informRule = $informRule . '||';

            $voice = $alertData['voice'];
            if($voice) {
                $informRule = $informRule . 'voice:Y';
            } else {
                $informRule = $informRule . 'voice:N';
            }

            // echo $alertRule . '</br>';
            // echo $informRule;
            $alertItem->setAlertRule($alertRule);
            $alertItem->setInformRule($informRule);

            $em = $this->getDoctrine()->getManager();
            $em->persist($alertItem);
            $em->flush();

            return $this->redirectToRoute('device_bind_success');
        } 

        if($device) {
            return $this->render('AcmeWebBundle:Wechat:devshow.html.twig', array('device' => $device, 'form' => $alertForm->createView()));
        } else {
            return $this->render('AcmeWebBundle:Wechat:devnull.html.twig');
        }
    }

    // private function generateCode(Request $request)
    // {
    //     $wechat_auth_url = $this->container->getParameter('wechat_auth_url');
    //     $wechat_appid = $this->container->getParameter('wechat_appid');
    //     $redirect_url = $request->getUri();

    //     $scope = $this->container->getParameter('wechat_auth_scope_base');

    //     $wechat_auth_url = sprintf($wechat_auth_url, $wechat_appid, urlencode($redirect_url), $scope, '1234');

    //     // return $this->redirect($wechat_auth_url);
    //     echo $url = $this->generateUrl('homepage');
    //     return $this->redirect($url);
    // }

    private function getOpenid(Request $request)
    {
        $logger = $this->container->get("my_service.logger"); 

        $session = $request->getSession();
        $openid = $session->get('user_openid');

        // $logger->info("user open id get from session: " . !$openid ? 'NULL' : $openid );
        $logger->info("user open id get from session: " . ( !isset($openid) ? 'NULL' : $openid ) );

        if( !$openid ) {
            if( $request->query->has('code') ) {                
                $code = $request->query->get('code');
                $state = $request->query->get('state');

                $wechat_auth_access_token_url = $this->container->getParameter('wechat_auth_access_token');
                $wechat_appid = $this->container->getParameter('wechat_appid');
                $wechat_app_secret = $this->container->getParameter('wechat_appsecret');

                $wechat_auth_access_token_url = sprintf($wechat_auth_access_token_url, $wechat_appid, $wechat_app_secret, $code);

                $logger->info("wechat_auth_access_token_url: " . $wechat_auth_access_token_url);

                // $response = $this->redirect($wechat_auth_access_token_url);
                // $response_content = $response->getContent();
                $response_content = file_get_contents($wechat_auth_access_token_url);
                $logger->info("response_content: " . $response_content);

                $json = json_decode($response_content);
                
                if(isset($json->{'openid'})) {
                    $openid = $json->{'openid'};
                    $session->set('user_openid', $openid);
                }
            }
        }

        return $openid;
    }

    private function removeSpace($str)
    {
        return strtolower(preg_replace("/\s+|　/", "", urldecode($str)));
    }

    private function getWechatParams($url)
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        list($appid, $timestamp, $nonceStr, $signature) = $webchatApi->getJsTicketListWithUrl($url);

        $ret = array('appid' => $appid, 'timestamp' => $timestamp, 'nonceStr' => $nonceStr, 'signature' => $signature);
        return $ret;
    }
}
