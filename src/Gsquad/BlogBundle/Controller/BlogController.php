<?php

namespace Gsquad\BlogBundle\Controller;

use Gsquad\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/{page}", name="blog", requirements={"page": "\d+"})
     */
    public function indexAction($page = 1)
    {
        $posts_count = $this->getDoctrine()
            ->getRepository('GsquadBlogBundle:Post')
            ->countPublishedTotal();

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
            'pagination' => $pagination,
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

        if (!$post) {
            throw $this->createNotFoundException('Aucun article correspondant.');
        }

        $comments = $em
            ->getRepository('GsquadBlogBundle:Comment')
            ->findAll();

        $formType = 'Gsquad\BlogBundle\Form\Type\CommentType';
        $newComment = new Comment();

        $form = $this->get('form.factory')->create($formType, $newComment);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $post->addComment($newComment);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newComment);
            $em->flush();

            $this->addFlash('info', 'L\'article a été ajouté !');
            return $this->redirectToRoute('single_post', array(
                'slug' => $slug
            ));
        }

        dump($post, $comments);
        return $this->render('blog/single.html.twig', array(
            'post' => $post,
            'comments' => $comments,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{category}", name="category")
     */
    public function categoryAction(Request $request, $page = 1)
    {
        $category = $request->get('category');
        dump($category);

        $posts_count = $this->getDoctrine()
            ->getRepository('GsquadBlogBundle:Post')
            ->countPublishedTotalByCategory($category);

        $posts = $this->getDoctrine()->getRepository('GsquadBlogBundle:Post')
            ->getPostsByCategory($category, $page);

        return $this->render('blog/index.html.twig', array(
            'posts' => $posts,
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
