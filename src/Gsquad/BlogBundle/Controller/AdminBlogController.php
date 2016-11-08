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

class AdminBlogController extends Controller
{
    /**
     * @Route("/add", name ="add")
     */
    public function addAction(Request $request)
    {
        $formType = 'Gsquad\BlogBundle\Form\Type\PostType';
        $newPost = new Post();

        $form = $this->get('form.factory')->create($formType, $newPost);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newPost);
            $em->flush();

            $this->addFlash('info', 'L\'article a été ajouté !');
            return $this->redirectToRoute('blog');
        }

        return $this->render('blog/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}