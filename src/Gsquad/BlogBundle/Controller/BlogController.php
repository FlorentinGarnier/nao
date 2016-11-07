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
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("blog/{slug}")//TODO à compléter
     */
    public function singleAction()
    {
        return $this->render('blog/single.html.twig');
    }
}
