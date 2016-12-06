<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 19:15
 */

namespace Gsquad\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class AdminObservationsController extends Controller
{
    /**
     * @Route("/observations", name="admin_observations")
     */
    public function adminObsAction()
    {
        return $this->render('admin/observations/observations.html.twig');
    }
}