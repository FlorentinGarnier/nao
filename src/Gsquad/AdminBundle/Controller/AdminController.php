<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 13:38
 */

namespace Gsquad\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function homeAction()
    {
        return $this->render('admin/home.html.twig');
    }

    /**
     * @Route("/mailing", name="mailing")
     */
    public function mailingAction()
    {
        $formType = 'Gsquad\AdminBundle\Form\Type\MailingType';
        $form = $this->get('form.factory')->create($formType);

        return $this->render('admin/mailing/mailing.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
