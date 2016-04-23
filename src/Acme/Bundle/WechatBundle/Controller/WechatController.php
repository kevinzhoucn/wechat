<?php

namespace Acme\Bundle\WechatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\Bundle\WechatBundle\Form\BindDeviceType;
use Acme\Bundle\UserBundle\Entity\User;
use Acme\Bundle\IotBundle\Entity\Device;

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

    public function airkissAction()
    {
        $wechatParams = $this->getWechatParams();        
        // return new Response(sprintf("appid: %s, timestamp: %s, nonce: %s, signature: %s", $appid, $timestamp, $nonceStr, $signature));
        return $this->render('AcmeWebBundle:Wechat:airkiss.html.twig', array('wechat' => $wechatParams));
    }
    
    /**
     * Bind a new Device to User.
     *
     */
    public function bindAction(Request $request)
    {
        $data = array();
        $form = $this->createForm(new BindDeviceType(), $data);
        $form->handleRequest($request);

        $logger = $this->container->get("my_service.logger");

        if ($form->isSubmitted() && $form->isValid()) {
            $bind_device = $form->getData();

            $username = $this->removeSpace($bind_device['phone']);
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

            $phones = array($username);

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
            return $this->redirectToRoute('device_bind_success');
        }

        return $this->render('AcmeWebBundle:Wechat:bind.html.twig', array(
                             'device' => $data,
                             'form' => $form->createView(),
                            ));
    }

    // public function bindSuccessAction( $sn, $phones )
    public function bindSuccessAction()
    {
        $url = $this->container->getParameter("");
        $wechatParams = $this->getWechatParams();
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

    private function removeSpace($str)
    {
        return strtolower(preg_replace("/\s+|　/", "", urldecode($str)));
    }

    private function getWechatParams($url = null)
    {
        $webchatApi = $this->container->get('acme.wechat.api');
        if($url) {
            list($appid, $timestamp, $nonceStr, $signature) = $webchatApi->getJsTicketSignatureList();
        } else {
            list($appid, $timestamp, $nonceStr, $signature) = $webchatApi->getJsTicketListWithUrl($url);
        }

        $ret = array('appid' => $appid, 'timestamp' => $timestamp, 'nonceStr' => $nonceStr, 'signature' => $signature);
        return $ret;
    }
}
