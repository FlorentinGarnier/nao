<?php

namespace Gsquad\PiafBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PiafController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        return $this->render('search/search.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
