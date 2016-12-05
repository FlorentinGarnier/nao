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
    public function indexAction()
    {
        $lastObs = $this->getDoctrine()
            ->getRepository('GsquadPiafBundle:Observation')
            ->findLatestObs(3);

        return $this->render('site/home.html.twig', array(
            'lastObs' => $lastObs
        ));
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $formType = 'Gsquad\CoreBundle\Form\Type\ContactType';
        $form = $this->get('form.factory')->create($formType);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data = $form->getData();

            $this->get('gsquad.mailer')->sendContactMail($data);

            $this->addFlash('notice', 'Merci ! Votre message a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('site/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/plan-site", name="plan_site")
     */
    public function planAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listCategories = $em
            ->getRepository('GsquadBlogBundle:Category')
            ->findAll();

        return $this->render('site/plan_site.html.twig', array(
            'categories' => $listCategories
        ));
    }
}
