<?php

namespace Gsquad\PiafBundle\Controller;

use Gsquad\PiafBundle\Entity\Observation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Gsquad\PiafBundle\Entity\Piaf;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PiafController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        $listPiafs = [];
        $choiceEspece = [];
        $choiceEspece['Pas d\'espèce précise'] = false;

        $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        //$listEspeces = $connection->fetchAll(
        //    'SELECT NOM_VERN FROM taxref'
        //);

        //On récupère tous les enregistrements de fetchAllNomVern
        $listEspeces = $piafRepository->fetchAllNomVern();

        foreach($listEspeces as $espece) {
            if($espece['nameVern'] != null) {
                $choiceEspece[$espece['nameVern']] = $espece['nameVern'];
            }
        }

        //Les résultats sont trié dans la methode fetchAllNomVern
        //asort($choiceEspece);

        $service = $this->container->get('gsquad_piaf.get_departements');

        $depts = $service->getDepartementsArray();

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('nameVern', TextType::class, array('required' => false))
            ->add('espece', ChoiceType::class,
                array('choices' => $choiceEspece
                ))
            ->add('departement', ChoiceType::class,
                array('choices' => $depts))
            ->add('search', SubmitType::class, array('label' => 'Lancer la recherche'))
            ->getForm();


        if($form->handleRequest($request)->isValid())
        {
            $name = $form["nameVern"]->getData();
            $departement = $form["departement"]->getData();
            $espece = $form["espece"]->getData();
            $listPiafs = [];

            if($departement) {
                $listObservations = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('GsquadPiafBundle:Observation')
                    ->findBy(
                        array('departement' => $departement)
                    );

                foreach ($listObservations as $observation) {
                    $piafTemp = $observation->getPiaf();

                    $posA = true;
                    $posB = true;

                    if($espece) {
                        if($piafTemp->getNameVern() === $espece) {
                            $posA = true;
                        } else {
                            $posA = false;
                        }
                        $posB = false;
                    }

                    if($name !== null && !$espece) {
                        $posA = strpos($this->removeAccents($piafTemp->getNameLatin()),($this->removeAccents($name)));
                        $posB = strpos($this->removeAccents($piafTemp->getNameVern()),($this->removeAccents($name)));
                    }

                    if($posA !== false || $posB !== false) {
                        if(!in_array($piafTemp, $listPiafs)) {
                            $piafTemp->setNbObservations(1);
                            $listPiafs[] = $piafTemp;
                        } else {
                            $key = array_search($piafTemp, $listPiafs);
                            $listPiafs[$key]->setNbObservations($listPiafs[$key]->getNbObservations() + 1);
                        }
                    }
                }

                usort($listPiafs, function ($a, $b)
                {
                    if ($a->getNbObservations() == $b->getNbObservations()) {
                        return 0;
                    }
                    return ($a->getNbObservations() > $b->getNbObservations()) ? -1 : 1;
                });
            }
            else {
                if($espece) {
//                    $sql = "SELECT ID, LB_NOM, NOM_VERN, NOM_VERN_ENG, HABITAT, ORDRE, FAMILLE FROM taxref WHERE NOM_VERN = :name";
//
//                    $stmt = $connection->prepare($sql);
//                    $stmt->bindValue("name", $espece);
//                    $stmt->execute();
//                    $results = $stmt->fetchAll();
                    $results = $piafRepository->findBy(['nameVern' => $espece]);
                }
                else {
//                    $sql = "SELECT LB_NOM, NOM_VERN, NOM_VERN_ENG, HABITAT, ORDRE, FAMILLE FROM taxref WHERE NOM_VERN = :name";
//
//                    $stmt = $connection->prepare($sql);
//                    $stmt->bindValue("name", $name);
//                    $stmt->execute();
//                    $results = $stmt->fetchAll();

                    $results = $piafRepository->findBy(['nameVern' => $name]);
                }
                dump($results);
                if(empty($results)) {
//                    $results = $connection->fetchAll(
//                        'SELECT LB_NOM, NOM_VERN FROM taxref'
//                    );

                    $results = $piafRepository->fetchAllNomVernLbNom();

                    $temp = [];

                    foreach ($results as $result) {

                        if(!in_array($result, $temp)) {

                            if($name === null) {
                                $posA = true;
                                $posB = true;
                            } else {
                                $posA = strpos($this->removeAccents($result['nameVern']),($this->removeAccents($name)));
                                $posB = strpos($this->removeAccents($result['lbNom']),($this->removeAccents($name)));
                            }

                            if($posA !== false) {
                                $temp[] = $result['nameVern'];
                            }
                            if($posB !== false) {
                                $temp[] = $result['lbNom'];
                            }
                        }
                    }

                    $results = [];

                    //TODO améliorer la requete en passant par le repo
                    if(!empty($temp)) {
                        $sql = "SELECT ID, LB_NOM, NOM_VERN, NOM_VERN_ENG, HABITAT, ORDRE, FAMILLE FROM taxref WHERE ";

                        for($i = 0; $i < count($temp); $i++) {
                            $sql = $sql."NOM_VERN = :name".$i." OR LB_NOM = :name".$i;
                            if($i != count($temp)-1) {
                                $sql = $sql." OR ";
                            }
                            /*
                            else {
                                $sql = $sql." LIMIT 0,15";
                            }
                            */
                        }

                        $stmt = $connection->prepare($sql);
                        for($i = 0; $i < count($temp); $i++) {
                            $stmt->bindValue("name".$i, $temp[$i]);
                        }
                        $stmt->execute();
                        $results = $stmt->fetchAll();
                    }
                }

                foreach ($results as $result) {
                    $habitat = $result->getHabitat();

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

                    //TODO A améliorer, je pense qu'on peut voir une autre approche
                    $piaf = new Piaf();
                    $piaf->setNameLatin($result['LB_NOM']);
                    $piaf->setFamily($result['FAMILLE']);
                    $piaf->setHabitat($habitat);
                    $piaf->setNameVernEng($result['NOM_VERN_ENG']);
                    $piaf->setNameVern($result['NOM_VERN']);
                    $piaf->setOrdre($result['ORDRE']);

                    $listPiafs[] = $piaf;
                }
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



    //TODO à voir aussi
    public function listAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM AcmeMainBundle:Article a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        // parameters to template
        return $this->render('AcmeMainBundle:Article:list.html.twig', array('pagination' => $pagination));
    }




    /**
     * @Route("/ajax/autocomplete/update/data", name="ajax_autocomplete")
     */
    public function updateDataAction(Request $request)
    {
        $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
        $data = $request->get('input');

//        $em = $this->getDoctrine()->getManager();

//        $connection = $em->getConnection();

//        $results = $connection->fetchAll(
//            'SELECT LB_NOM, NOM_VERN FROM taxref'
//        );

        $results = $piafRepository->fetchAllNomVernLbNom();


        $temp = [];

        $speciesList = '<ul id="matchList">';
        foreach ($results as $result) {
            if(!in_array($result, $temp)) {
                $temp[] = $result;
                dump($result);
                $posA = strpos($this->removeAccents($result['lbNom']),($this->removeAccents($data)));
                $posB = strpos($this->removeAccents($result['nameVern']),($this->removeAccents($data)));

                if($posA !== false) {
                    $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['lbNom']); // Replace text field input by bold one
                    $speciesList .= '<li id="'.$result['nameVern'].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
                }
                if($posB !== false) {
                    $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['nameVern']); // Replace text field input by bold one
                    $speciesList .= '<li id="'.$result['nameVern'].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
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
