<?php

namespace Gsquad\BlogBundle\Controller;

use Gsquad\BlogBundle\Entity\Category;
use Gsquad\BlogBundle\Entity\Comment;
use Gsquad\BlogBundle\Entity\Post;
use Gsquad\BlogBundle\Form\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
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
        $user = $this->getUser()->getUsername();

        $formType = 'Gsquad\BlogBundle\Form\Type\CommentType';
        $newComment = new Comment();

        $form = $this->get('form.factory')->create($formType, $newComment);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $newComment->setAuthor($user);
            $post->addComment($newComment);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newComment);
            $em->flush();

            $this->addFlash('info', 'Le commentaire a été ajouté !');
            return $this->redirectToRoute('single_post', array(
                'slug' => $post->getSlug()
            ));
        }

        return $this->render('blog/single.html.twig', array(
            'post' => $post,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/categorie/{slug}", name="category")
     * @Route("/categorie/{slug}/{page}", name="category", requirements={"page": "\d+"})
     * @ParamConverter("category", class="GsquadBlogBundle:Category")
     */
    public function categoryAction(Category $category, $page = 1)
    {
        $posts_count = $this->getDoctrine()
            ->getRepository('GsquadBlogBundle:Post')
            ->countPublishedTotalByCategory($category->getId());

        $pagination = array(
            'page' => $page,
            'route' => 'category',
            'pages_count' => ceil($posts_count / 5),
            'route_params' => array(
                'slug' => $category->getSlug()
            )
        );

        $posts = $this->getDoctrine()->getRepository('GsquadBlogBundle:Post')
            ->getPostsByCategory($category->getId(), $page);

        $title = $category->getName();

        return $this->render('blog/index.html.twig', array(
            'posts' => $posts,
            'pagination' => $pagination,
            'title' => $title
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

    /**
     * @Route("/", name="search_results")
     */
    public function searchAction(Request $request, $page = 1)
    {
        $search = $request->get('terme');

        $posts_count = $this->getDoctrine()
            ->getRepository('GsquadBlogBundle:Post')
            ->countPublishedTotalBySearch($search);

        $pagination = array(
            'page' => $page,
            'route' => 'search_results',
            'pages_count' => ceil($posts_count / 5),
            'route_params' => array()
        );

        $posts = $this->getDoctrine()->getRepository('GsquadBlogBundle:Post')
            ->getPostsBySearch($search);

        $title = "Résultat de votre recherche : " . $search;

        return $this->render('blog/index.html.twig', array(
            'posts' => $posts,
            'pagination' => $pagination,
            'title' => $title
        ));
    }

    public function searchBarBlogAction()
    {
        return $this->render(':blog:search_blog.html.twig');
    }
}
