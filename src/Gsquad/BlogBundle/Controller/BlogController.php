<?php

namespace Gsquad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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



        return $this->render('blog/index.html.twig', array(
            'posts' => $listPosts
        ));
    }

    /**
     * @Route("/{slug}", name="single_post")//TODO à compléter
     */
    public function singleAction(Request $request)
    {
        $slug = $request->get('slug');

        $em = $this->getDoctrine()->getManager();
        $post = $em
            ->getRepository('GsquadBlogBundle:Post')
            ->findOneBy(array(
                'slug' => $slug));

        $comments = $em
            ->getRepository('GsquadBlogBundle:Comment')
            ->findByPost($post->getId());

        dump($post, $comments);
        return $this->render('blog/single.html.twig', array(
            'post' => $post,
            'comments' => $comments
        ));
    }

    public function navigationBlogAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listCategories = $em
            ->getRepository('GsquadBlogBundle:Category')
            ->findAll();

        return $this->render('blog/navigation_blog.html.twig', array(
            'categories' =>$listCategories,
        ));
    }
}
