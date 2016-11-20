<?php

namespace Gsquad\BlogBundle\Controller;

use Gsquad\BlogBundle\Entity\Category;
use Gsquad\BlogBundle\Entity\Comment;
use Gsquad\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
     * @ParamConverter("post", class="GsquadBlogBundle:Post")
     */
    public function singleAction(Post $post, Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $comments = $em
            ->getRepository('GsquadBlogBundle:Comment')
            ->findAll();

        $formType = 'Gsquad\BlogBundle\Form\Type\CommentType';
        $newComment = new Comment();

        $form = $this->get('form.factory')->create($formType, $newComment);

        //TODO Limiter commentaires aux connectés
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $newComment->setAuthor($user->getUsername());
            $post->addComment($newComment);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newComment);
            $em->flush();

            $this->addFlash('info', 'L\'article a été ajouté !');
            return $this->redirectToRoute('single_post', array(
                'slug' => $post->getSlug()
            ));
        }

        return $this->render('blog/single.html.twig', array(
            'post' => $post,
            'comments' => $comments,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{slug}", name="category")
     * @Route("/{slug}/{page}", name="category")
     * @ParamConverter("category", class="GsquadBlogBundle:Category")
     */
    public function categoryAction(Category $category, $page = 1)
    {
        $posts_count = $this->getDoctrine()
            ->getRepository('GsquadBlogBundle:Post')
            ->countPublishedTotalByCategory($category->getId());

        $pagination = array(
            'page' => $page,
            'route' => 'blog',
            'pages_count' => ceil($posts_count / 5),
            'route_params' => array()
        );

        $posts = $this->getDoctrine()->getRepository('GsquadBlogBundle:Post')
            ->getPostsByCategory($category->getId(), $page);

        return $this->render('blog/category.html.twig', array(
            'posts' => $posts,
            'pagination' => $pagination
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
