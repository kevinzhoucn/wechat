<?php

namespace Acme\Bundle\WebBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\Bundle\IotBundle\Entity\Device;
use Acme\Bundle\IotBundle\Form\DeviceType;
use Symfony\Component\HttpFoundation\Request;

class DeviceController extends Controller
{
    public function mqttMessageAction()
    {
        return $this->render('AcmeWebBundle:Frontend\Device\MQTT:message.html.twig');
    }

    public function mqttItemMessageAction($id)
    {
        $user = $this->getUser();
        $username = $user->getUsername();

        $device = $this->getDoctrine()
                       ->getRepository('AcmeIotBundle:Device')
                       ->findOneByDeviceIdJoinedToUser($id, $username);

        return $this->render('AcmeWebBundle:Frontend\Device\MQTT:message_item.html.twig',
                             array('devname' => $device->getName(), 'username' => $user->getUsername())
                            );
    }

    public function mqttTransferAction()
    {
        $user = $this->getUser();
        $devices = $user->getDevices();

        return $this->render('AcmeWebBundle:Frontend\Device\MQTT:transfer.html.twig', array('devices' => $devices, 'username' => $user->getUsername()));
    }

    public function devListAction(Request $request)
    {
        $user = $this->getUser();
        $deviceKey = $user->getDeviceKey();
        
        $devices = $user->getDevices();        

        $test_str = "|12345";

        $device = new Device();
        $form = $this->createForm(new DeviceType(), $device);
        // $deleteForm = $this->createDeleteForm($device);

        // $logger = $this->container->get("my_service.logger");
        // $logger->info(sprintf("==========Log from dev list: user name: %s", $user->getUsername()));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $logger->info(sprintf("==========Log from dev list: device submit dev name: %s", $device->getName()));

            $em = $this->getDoctrine()->getManager();

            $device_item = $this->getDoctrine()
                       ->getRepository('AcmeIotBundle:Device')
                       ->findOneByNameJoinedToUser($device->getName(), $user->getUsername());

            // var_dump($device);

            if(!$device_item) {
                // $logger->info(sprintf("==========Log from dev list: device submit non exist_item_id."));
                $user = $user->addDevice($device);
                $device->setUser($user);
                $em->persist($user);
                $em->persist($device);
                $em->flush();
            } else {
                // $logger->info(sprintf("==========Log from dev list: device submit exist_item_id."));
            }

            // return $this->redirectToRoute('user_show', array('id' => $user->getId()));
            return $this->redirectToRoute('acme_frontend_device_mqtt_devlist');
        }

        return $this->render('AcmeWebBundle:Frontend\Device\MQTT:devlist.html.twig', array(
                             'deviceKey' => $deviceKey,
                             'device' => $device,
                             'devices' => $devices,
                             'form' => $form->createView()
        ));
    }

    public function devDeleteAction(Request $request, Device $device)
    {
        if($device) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($device);
            $em->flush();
        }
        return $this->redirectToRoute('acme_frontend_device_mqtt_devlist');
    }

    private function createDeleteForm(Device $device)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acme_frontend_device_mqtt_dev_delete', array('id' => $device->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
