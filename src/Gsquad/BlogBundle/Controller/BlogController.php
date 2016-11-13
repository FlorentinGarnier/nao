<?php

namespace Gsquad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/{page}", name="blog", requirements={"page": "\d+"})
     */
    public function indexAction(Request $request, $page = 1)
    {
        $posts_count = $this->getDoctrine()
            ->getRepository('GsquadBlogBundle:Post')
            ->countPublishedTotal();

        dump($posts_count);

        $pagination = array(
            'page' => $page,
            'route' => 'blog',
            'pages_count' => ceil($posts_count / 5),
            'route_params' => array()
        );

        $posts = $this->getDoctrine()->getRepository('GsquadBlogBundle:Post')
            ->getAllPosts($page);

        return $this->render('blog/index.html.twig', array(
            'posts' => $posts,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/{slug}", name="single_post")
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
