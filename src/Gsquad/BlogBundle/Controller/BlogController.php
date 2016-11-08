<?php

namespace Gsquad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listPosts = $em
            ->getRepository('GsquadBlogBundle:Post')
            ->findAll();

        $listCategories = $em
            ->getRepository('GsquadBlogBundle:Category')
            ->findAll();

        return $this->render('blog/index.html.twig', array(
            'categories' =>$listCategories,
            'posts' => $listPosts
        ));
    }

    /**
     * @Route("/{slug}")//TODO à compléter
     */
    public function singleAction()
    {
        return $this->render('blog/single.html.twig');
    }


}
