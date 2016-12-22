<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 19:15
 */

namespace Gsquad\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;


class AdminObservationsController extends Controller
{
    /**
     * @Route("/observations", name="admin_observations")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_CHERCHEUR')")
     */
    public function adminObsAction()
    {
        $observationRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Observation');
        $observations = $observationRepository->findNotValidObs();

        $choiceEspece = [];
        $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
        $listEspeces = $piafRepository->findAll();

        foreach($listEspeces as $espece) {
            if($espece->getNameVern() != null && !in_array($espece->getNameVern(), $choiceEspece)) {
                $choiceEspece[] = $espece->getNameVern();
            }
        }

        function wd_remove_accents($str, $charset='utf-8')
        {
            $str = htmlentities($str, ENT_NOQUOTES, $charset);
            $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
            $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
            $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
            return $str;
        }

        usort($choiceEspece, function($a, $b)
        {
            return strcmp(strtolower(wd_remove_accents($a)), strtolower(wd_remove_accents($b)));
        });

        return $this->render('admin/observations/observations.html.twig', [
            'observations' => $observations,
            'especes' => $choiceEspece,
        ]);
    }

    /**
     * @Route("/postvalidation", name="admin_post_validation")
     */
    public function updateDataAction(Request $request)
    {
        $id = $request->get('id');
        $nameVern = $request->get('espece');

        $observationRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Observation');
        $observation = $observationRepository->find($id);

        $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
        $piaf = $piafRepository->findOneBy(array(
            'nameVern' => $nameVern
        ));
        if($piaf == null) {
            $nameVern = $nameVern.' ';
            $piaf = $piafRepository->findOneBy(array(
                'nameVern' => $nameVern
            ));
        }

        $piaf->setNbObservations($piaf->getNbObservations() + 1);

        $em = $this->getDoctrine()->getManager();

        $observation->setValid(true);
        $em->persist($observation);

        if($observation->getPhoto != null) {
            $piaf->setPhoto($observation->getPhoto);
        }

        $em->persist($piaf);
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array('status' => "validated"));
        return $response;
    }

    /**
     * @Route("/deletevalidation", name="admin_post_delete")
     */
    public function deleteDataAction(Request $request)
    {
        $id = $request->get('id');

        $observationRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Observation');
        $observation = $observationRepository->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($observation);
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array('status' => "deleted"));
        return $response;
    }
}