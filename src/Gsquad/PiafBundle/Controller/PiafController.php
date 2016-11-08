<?php

namespace Gsquad\PiafBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Gsquad\PiafBundle\Entity\Piaf;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PiafController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        $piaf = new Piaf();
        $listPiafs = [];

        $form = $this->createFormBuilder($piaf)
            ->add('name', TextType::class)
            ->add('search', SubmitType::class, array('label' => 'Lancer la recherche'))
            ->getForm();

        if($form->handleRequest($request)->isValid())
        {
            $name = $form["name"]->getData();
            $listPiafs = [];

            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();


            $results = $connection->fetchAll(
                "SELECT * FROM taxref WHERE NOM_VERN = '".$name."'"
            );

            foreach ($results as $result) {
                $habitat = $result['HABITAT'];

                switch($habitat) {
                    case 1:
                        $habitat = "Marin";
                        break;
                    case 2:
                        $habitat = "Eau douce";
                        break;
                    case 3:
                        $habitat = "Terrestre";
                        break;
                    case 4:
                        $habitat = "Marin & Eau douce";
                        break;
                    case 5:
                        $habitat = "Marin & Terrestre";
                        break;
                    case 6:
                        $habitat = "Eau saumâtre";
                        break;
                    case 7:
                        $habitat = "Continental (Terrestre et/ou eau douce)";
                        break;
                    case 8:
                        $habitat = "Continental (Terrestre et eau douce)";
                        break;
                    default:
                        $habitat = "Inconnu";
                }

                $piaf = new Piaf();
                $piaf->setName($result['NOM_VERN']);
                $piaf->setFamily($result['FAMILLE']);
                $piaf->setHabitat($habitat);
                $piaf->setNameVernEng($result['NOM_VERN_ENG']);
                $piaf->setNameVern($result['NOM_VERN']);
                $piaf->setOrdre($result['ORDRE']);

                $listPiafs[] = $piaf;
            }

            return $this->render('search/search.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'form' => $form->createView(),
                'list_piafs' => $listPiafs,
            ]);
        }

        return $this->render('search/search.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
            'list_piafs' => $listPiafs,
        ]);
    }

    /**
     * @Route("/ajax/autocomplete/update/data", name="ajax_autocomplete_countries")
     */
    public function updateDataAction(Request $request)
    {
        $data = $request->get('input');

        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();


        $results = $connection->fetchAll(
            'SELECT NOM_VERN FROM taxref'
        );

        $temp = [];

        $speciesList = '<ul id="matchList">';
        foreach ($results as $result) {
            if(!in_array($result, $temp)) {
                $temp[] = $result;
                $pos = strpos($this->removeAccents($result['NOM_VERN']),($this->removeAccents($data)));

                if($pos !== false) {
                    $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['NOM_VERN']); // Replace text field input by bold one
                    $speciesList .= '<li id="'.$result['NOM_VERN'].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
                }
            }
        }
        $speciesList .= '</ul>';

        $response = new JsonResponse();
        $response->setData(array('speciesList' => $speciesList));
        return $response;
    }

    private function removeAccents($string) {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
    }
}
