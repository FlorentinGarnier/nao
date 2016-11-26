<?php

namespace Gsquad\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('site/home.html.twig');
    }

    /**
     * @Route("/adhesion", name="adhesion")
     */
    public function adhesionAction()
    {
        return $this->render('site/adhesion.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('site/contact.html.twig');
    }
}
