<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 08/11/2016
 * Time: 15:49
 */

namespace Gsquad\BlogBundle\Controller;


use Gsquad\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AdminBlogController
 * @package Gsquad\BlogBundle\Controller
 * @Security("has_role('ROLE_CHERCHEUR') and has_role('ROLE_ADMIN')") //TODO Revoir la sécurité en fonction des rôles
 */
class AdminBlogController extends BlogController
{
    /**
     * @Route("/admin", name="admin-index")
     */
    public function adminIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listPosts = $em
            ->getRepository('GsquadBlogBundle:Post')
            ->findAll();

        $form = $this->get('form.factory')->create();

        return $this->render('blog/admin/index.html.twig', array(
            'listPosts' => $listPosts,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/add", name ="add")
     */
    public function addAction(Request $request)
    {
        $user = $this->getUser();

        $formType = 'Gsquad\BlogBundle\Form\Type\PostType';
        $newPost = new Post();

        $form = $this->get('form.factory')->create($formType, $newPost);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $slug = $this->get('gsquad_blog.slugger')->slugify($newPost->getTitle());
            $newPost->setSlug($slug);
            $newPost->setAuthor($user->getUsername());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newPost);
            $em->flush();

            $this->addFlash('info', 'L\'article a été ajouté !');
            return $this->redirectToRoute('blog');
        }

        return $this->render('blog/admin/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name ="edit")
     */
    public function editAction(Request $request, Post $post)
    {
        $formType = 'Gsquad\BlogBundle\Form\Type\PostType';

        $form = $this->get('form.factory')->create($formType, $post);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'Element bien modifié.');

            return $this->redirectToRoute('admin-index');
        }

        return $this->render('blog/admin/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Request $request, Post $post)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();

            $this->addFlash('info', "L'élément a bien été supprimé.");
            dump('supprimé');
        }
        return $this->redirectToRoute('admin-index');
    }
}