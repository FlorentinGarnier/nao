<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 19:14
 */

namespace Gsquad\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminUsersController extends Controller
{
    /**
     * @Route("/users", name="admin_users")
     */
    public function adminUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em
            ->getRepository('GsquadUserBundle:User')
            ->findAll();

        $form = $this->get('form.factory')->create();

        return $this->render('admin/users/users.html.twig', array(
            'users' => $users,
            'form' => $form->createView()
        ));
    }
}