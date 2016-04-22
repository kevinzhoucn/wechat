<?php

namespace Acme\Bundle\IotBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\Bundle\IotBundle\Entity\Device;
use Acme\Bundle\IotBundle\Form\BindDeviceType;

use Acme\Bundle\UserBundle\Entity\User;

// use Acme\Bundle\IotBundle\Form\DeviceType;

/**
 * Device controller.
 *
 */
class DeviceController extends Controller
{ 
    /**
     * Bind a new Device to User.
     *
     */
    public function bindAction(Request $request)
    {
        $data = array();
        $form = $this->createForm(new BindDeviceType(), $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bind_device = $form->getData();

            $username = $bind_device['username'];
            $device_uuid = $bind_device['uuid'];
            
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AcmeUserBundle:User')
                       ->find($username);

            $device = $em->getRepository('AcmeIotBundle:Device')
                         ->findOneBy(array('uuid' => $device_uuid));

            if(!$user) {
                $user = new User();
                $user->setUsername($username);
            }

            if(!$device) {
                $device = new Device();
                $device->setUuid($device_uuid);
            }

            $user->addDevice($device);

            $em->persist($device);
            $em->persist($user);
            $em->flush();

            echo 'Bind success!';

            // return $this->redirectToRoute('device_show', array('id' => $device->getId()));
        }

        return $this->render('AcmeIotBundle:Device:bind.html.twig', array(
                             'device' => $data,
                             'form' => $form->createView(),
                            ));
    }


    // /**
    //  * Lists all Device entities.
    //  *
    //  */
    // public function indexAction()
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $devices = $em->getRepository('AcmeIotBundle:Device')->findAll();

    //     return $this->render('device/index.html.twig', array(
    //         'devices' => $devices,
    //     ));
    // }

    // /**
    //  * Creates a new Device entity.
    //  *
    //  */
    // public function newAction(Request $request)
    // {
    //     $device = new Device();
    //     $form = $this->createForm(new DeviceType(), $device);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($device);
    //         $em->flush();

    //         return $this->redirectToRoute('device_show', array('id' => $device->getId()));
    //     }

    //     return $this->render('device/new.html.twig', array(
    //         'device' => $device,
    //         'form' => $form->createView(),
    //     ));
    // }

    // /**
    //  * Finds and displays a Device entity.
    //  *
    //  */
    // public function showAction(Device $device)
    // {
    //     $deleteForm = $this->createDeleteForm($device);

    //     return $this->render('device/show.html.twig', array(
    //         'device' => $device,
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    // /**
    //  * Displays a form to edit an existing Device entity.
    //  *
    //  */
    // public function editAction(Request $request, Device $device)
    // {
    //     $deleteForm = $this->createDeleteForm($device);
    //     $editForm = $this->createForm(new DeviceType(), $device);
    //     $editForm->handleRequest($request);

    //     if ($editForm->isSubmitted() && $editForm->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($device);
    //         $em->flush();

    //         return $this->redirectToRoute('device_edit', array('id' => $device->getId()));
    //     }

    //     return $this->render('device/edit.html.twig', array(
    //         'device' => $device,
    //         'edit_form' => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    // /**
    //  * Deletes a Device entity.
    //  *
    //  */
    // public function deleteAction(Request $request, Device $device)
    // {
    //     $form = $this->createDeleteForm($device);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($device);
    //         $em->flush();
    //     }

    //     return $this->redirectToRoute('device_index');
    // }

    // /**
    //  * Creates a form to delete a Device entity.
    //  *
    //  * @param Device $device The Device entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Device $device)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('device_delete', array('id' => $device->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }
}
