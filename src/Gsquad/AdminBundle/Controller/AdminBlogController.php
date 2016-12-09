<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 08/11/2016
 * Time: 15:49
 */

namespace Gsquad\AdminBundle\Controller;


use Gsquad\BlogBundle\Entity\Category;
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
     * @Security("has_role('ROLE_REDACTEUR')")
     */
    public function adminIndexAction()
    {
        $lastLogin = $this->getUser()->getLastLogin();

        $em = $this->getDoctrine()->getManager();

        $listPosts = $em
            ->getRepository('GsquadBlogBundle:Post')
            ->getPendingPosts();

        $listCategories = $em
            ->getRepository('GsquadBlogBundle:Category')
            ->findAll();

        $listComments = $em
            ->getRepository('GsquadBlogBundle:Comment')
            ->getNewComments($lastLogin);

        $form = $this->get('form.factory')->create();

        return $this->render('admin/blog/index.html.twig', array(
            'listPosts' => $listPosts,
            'listCategories' => $listCategories,
            'listComments' => $listComments,
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
        $formCategory = $this->get('form.factory')->create('Gsquad\BlogBundle\Form\Type\CategoryType');

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

            return $this->redirectToRoute('blog');
        }

        return $this->render('admin/blog/add.html.twig', array(
            'form' => $form->createView(),
            'formCategory' => $formCategory->createView()
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
        $formCategory = $this->get('form.factory')->create('Gsquad\BlogBundle\Form\Type\CategoryType');

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if($form->get('submit')->isClicked()) {
                $post->setStatus('en attente');
            } elseif ($form->get('publish')->isClicked()) {
                $post->setStatus('publié');
            }

            $title = $form->getData()->getTitle();
            $slug = $this->get('gsquad.slugger')->slugify($title);
            $post->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'Article modifié.');

            return $this->redirectToRoute('admin_blog_posts');
        }

        return $this->render('admin/blog/add.html.twig', array(
            'form' => $form->createView(),
            'formCategory' => $formCategory->createView()
        ));
    }

    /**
     * @Route("/blog/add-category", name="category_add")
     */
    public function addCategoryAction(Request $request)
    {
        $form = $this->get('form.factory')->create('Gsquad\BlogBundle\Form\Type\CategoryType');

        return $this->render('admin/blog/add_category.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/blog/edit-category/{id}", name ="category_edit")
     * @Security("has_role('ROLE_REDACTEUR')")
     */
    public function editCategoryAction(Category $category)
    {
        $form = $this->get('form.factory')->create('Gsquad\BlogBundle\Form\Type\CategoryType', $category);

        return $this->render('admin/blog/edit_category.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }

    /**
     * @Route("/blog/save-new-category", name="new_category")
     */
    public function saveNewCategoryAction(Request $request)
    {
        $category = new Category();
        if ($request->isMethod('POST')) {
            $name = $request->request->get('category')['name'];
            $category->setName($name);
            $slug = $this->get('gsquad.slugger')->slugify($name);
            $category->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('info', 'Catégorie ajoutée');

            return $this->redirectToRoute('admin_blog_categories');
        }
    }

    /**
     * @Route("/blog/save-category/{id}", name="save_category")
     */
    public function saveCategoryAction(Request $request, Category $category)
    {
       if($request->isMethod('POST')){
           $name = $request->request->get('category')['name'];
           $category->setName($name);
           $slug = $this->get('gsquad.slugger')->slugify($name);
           $category->setSlug($slug);

           $em = $this->getDoctrine()->getManager();
           $em->flush();
           $this->addFlash('info', 'Modification enregistrée');
       }
       return $this->redirectToRoute('admin_blog_categories');
    }

    /**
     * @Route("/blog/{param}/{id}", name="delete")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $param, $id)
    {
        $entity = 'Gsquad\BlogBundle\Entity\\' . $param;
        $element = $this
            ->getDoctrine()
            ->getRepository($entity)
            ->find($id);

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($element);
            $em->flush();

            $this->addFlash('info', "L'élément a bien été supprimé.");
        }

        if($param === 'Category') $param = 'categorie';

        $route = 'admin_blog_' . lcfirst($param) . 's';
        return $this->redirectToRoute($route);
    }

    /**
     * @Route("/blog/posts", name="admin_blog_posts")
     * @Security("has_role('ROLE_REDACTEUR')")
     */
    public function postsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listPosts = $em
            ->getRepository('GsquadBlogBundle:Post')
            ->findAll();

        $form = $this->get('form.factory')->create();

        return $this->render('admin/blog/posts.html.twig', array(
            'listPosts' => $listPosts,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/blog/categories", name="admin_blog_categories")
     * @Security("has_role('ROLE_REDACTEUR')")
     */
    public function categoriesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listCategories = $em
            ->getRepository('GsquadBlogBundle:Category')
            ->findAll();

        $form = $this->get('form.factory')->create();

        return $this->render('admin/blog/categories.html.twig', array(
            'listCategories' => $listCategories,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/blog/comments", name="admin_blog_comments")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function commentsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listComments = $em
            ->getRepository('GsquadBlogBundle:Comment')
            ->findAll();

        $form = $this->get('form.factory')->create();

        return $this->render('admin/blog/comments.html.twig', array(
            'listComments' => $listComments,
            'form' => $form->createView()
        ));
    }
}
