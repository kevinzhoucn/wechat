<?php

namespace Acme\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\Bundle\UserBundle\Entity\User;
use Acme\Bundle\UserBundle\Form\UserIotType;

/**
 * User controller.
 *
 */
class UserIotController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AcmeUserBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserIotType(), $user);
        $form->handleRequest($request);

        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $username = $data->getUsername();

            $em = $this->getDoctrine()->getManager();
            $userExists = $em->getRepository('AcmeUserBundle:User')->findByUsername($username);

            if ($userExists) {
                $error = "该用户已被注册！";
            } else {
                $em->persist($user);
                $em->flush();

                $username = $user->getUsername();
                // $password = $user->getPassword();
                $password = $user->getDeviceKey();

                $this->updatePropertyFile($username, $password);

                // return $this->redirectToRoute('acme_frontend_device_mqtt_devlist');
                return $this->redirect('/user/login?regstatus=1');
            }
        }

        return $this->render('AcmeWebBundle:Frontend\User:new.html.twig', array(
                             'user' => $user,
                             'form' => $form->createView(),
                             'error' => $error
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm(new UserType(), $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function updatePropertyFile($username, $password)
    {
        // Update users group properties file
        $file_user = $this->getParameter("mqtt_user_properties_file");
        $file_group = fopen($this->getParameter("mqtt_groups_properties_file"), 'r');
        $file_content = "";
        while(false !== ($s = fgets($file_group))) {
            if(strpos($s, "users") === 0) {
                $s = rtrim($s, "\r\n");
                $file_content = $file_content . $s . "|" . $username . "\r\n";
            } else {
                $file_content = $file_content . $s;
            }
        }
        fclose($file_group);
        
        $file_group = fopen($this->getParameter("mqtt_groups_properties_file"), 'w');
        fwrite($file_group, $file_content);
        fclose($file_group);        

        // Update users name and password to user.properities
        $new_user = $username . "=" . $password . "\r\n";
        file_put_contents($file_user, $new_user, FILE_APPEND|LOCK_EX);
    }
}
