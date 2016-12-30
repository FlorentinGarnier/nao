<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 19:14
 */

namespace Gsquad\AdminBundle\Controller;

use Gsquad\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class AdminUsersController extends Controller
{
    /**
     * @Route("/users", name="admin_users")
     * @Security("has_role('ROLE_ADMIN')")
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
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/users/delete/{id}", name="user_delete")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteUserAction(Request $request, User $user)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('info', "L'utilisateur a été supprimé.");
        }
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/users/edit/{id}", name="user_edit")
     */
    public function editUserAction(User $user, Request $request)
    {
        if ($request->isMethod('POST')) {
            $newRoles = [
                $request->request->get('new_role_site'),
                $request->request->get('new_role_blog')
            ];
            $user->setRoles($newRoles);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', "L'utilisateur a été modifié.");
        }
        return $this->redirectToRoute('admin_users');
    }
}
