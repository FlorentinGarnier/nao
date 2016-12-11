<?php

namespace Gsquad\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MarketingController extends Controller
{
    /**
     * @Route("/participez-etude-observation-oiseaux")
     */
    public function indexAction()
    {
        $random = rand(0, 1);

        if ($random) {
            return $this->render('Marketing/index_a_content.html.twig');
        } else {
            return $this->render('Marketing/index_b_content.html.twig');
        }

    }
}
