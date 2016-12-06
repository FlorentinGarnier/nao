<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 08/11/2016
 * Time: 15:49
 */

namespace Gsquad\AdminBundle\Controller;


use Gsquad\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AdminBlogController
 * @package Gsquad\BlogBundle\Controller
 */
class AdminBlogController extends Controller
{
    /**
     * @Route("/blog", name="admin_blog")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listPosts = $em
            ->getRepository('GsquadBlogBundle:Post')
            ->findAll();

        $form = $this->get('form.factory')->create();

        return $this->render('admin/blog/index.html.twig', array(
            'listPosts' => $listPosts,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/blog/add", name ="post_add")
     * @Security("has_role('ROLE_CONTRIBUTEUR')")
     */
    public function addAction(Request $request)
    {
        $user = $this->getUser();

        $formType = 'Gsquad\BlogBundle\Form\Type\PostType';
        $newPost = new Post();

        $form = $this->get('form.factory')->create($formType, $newPost);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $slug = $this->get('gsquad.slugger')->slugify($newPost->getTitle());
            $newPost->setSlug($slug);
            $newPost->setAuthor($user->getUsername());

            if($form->get('submit')->isClicked()) {
                $newPost->setStatus('en attente');
            } elseif ($form->get('publish')->isClicked()) {
                $newPost->setStatus('publié');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($newPost);
            $em->flush();

            $this->addFlash('info', 'L\'article a été ajouté !');
            return $this->redirectToRoute('blog');
        }

        return $this->render('admin/blog/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/blog/edit/{id}", name ="post_edit")
     * @Security("has_role('ROLE_REDACTEUR')")
     */
    public function editAction(Request $request, Post $post)
    {
        $formType = 'Gsquad\BlogBundle\Form\Type\PostType';

        $form = $this->get('form.factory')->create($formType, $post);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if($form->get('submit')->isClicked()) {
                $post->setStatus('en attente');
            } elseif ($form->get('publish')->isClicked()) {
                $post->setStatus('publié');
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'Element bien modifié.');

            return $this->redirectToRoute('admin_blog');
        }

        return $this->render('admin/blog/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/blog/delete/{id}", name="post_delete")
     * @Security("has_role('ROLE_ADMIN')")
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
        return $this->redirectToRoute('admin_blog');
    }
}
