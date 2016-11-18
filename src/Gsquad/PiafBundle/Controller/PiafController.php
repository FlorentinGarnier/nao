<?php

namespace Gsquad\PiafBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PiafController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        $listPiafsObserves = [];
        $choiceEspece = [];
        $choiceEspece['Pas d\'espèce précise'] = false;

        $observationRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Observation');

        $listEspeces = $observationRepository->findAll();

        foreach($listEspeces as $espece) {
            if($espece->getPiaf()->getNameVern() != null && !array_key_exists($espece->getPiaf()->getNameVern(), $choiceEspece)) {
                $choiceEspece[$espece->getPiaf()->getNameVern()] = rtrim($espece->getPiaf()->getNameVern()," ");
            }
        }

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

        if($this->getUser()) {
            if(
                in_array('ROLE_CHERCHEUR', $this->getUser()->getRoles()) ||
                in_array('ROLE_ADMIN', $this->getUser()->getRoles()) ||
                in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())
            ){
                $form
                    ->add('latitude', NumberType::class, array('required' => false))
                    ->add('longitude', NumberType::class, array('required' => false));
            }
        }

        if($form->handleRequest($request)->isValid() || isset($_POST["espece"]))
        {
            if(isset($_POST["espece"])) {
                if($_POST["espece"] == '') {
                    $name = null;
                }
                else {
                    $name = $_POST["espece"];
                }
                $departement = false;
                $espece = false;
            }
            else {
                $name = $form["nameVern"]->getData();
                $departement = $form["departement"]->getData();
                $espece = $form["espece"]->getData();
            }

            $listPiafsObserves = [];

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
                        $posA = strpos($this->removeAccents($piafTemp->getLbNom()),($this->removeAccents($name)));
                        $posB = strpos($this->removeAccents($piafTemp->getNameVern()),($this->removeAccents($name)));
                    }

                    if($posA !== false || $posB !== false) {
                        if(!in_array($piafTemp, $listPiafsObserves)) {
                            $piafTemp->setNbObservations(1);
                            $listPiafsObserves[] = $piafTemp;
                        } else {
                            $key = array_search($piafTemp, $listPiafsObserves);
                            $listPiafsObserves[$key]->setNbObservations($listPiafsObserves[$key]->getNbObservations() + 1);
                        }
                    }
                }

                usort($listPiafsObserves, function ($a, $b)
                {
                    if ($a->getNbObservations() == $b->getNbObservations()) {
                        return 0;
                    }
                    return ($a->getNbObservations() > $b->getNbObservations()) ? -1 : 1;
                });

                $service = $this->container->get('gsquad_piaf.set_habitat');

                $listPiafsObserves = $service->setHabitats($listPiafsObserves);
            }
            else {
                $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');

                if($espece) {
                    $results = $piafRepository->findObservedPiafBy($espece);
                }
                else {
                    $results = $piafRepository->findObservedPiafBy($name);
                }

                if(empty($results)) {
                    $results = $piafRepository->findAllObservedPiaf();

                    $temp = [];

                    foreach ($results as $result) {

                        if(!in_array($result, $temp)) {
                            if($name === null) {
                                $posA = true;
                                $posB = true;
                            } else {
                                $posA = strpos($this->removeAccents($result->getNameVern()),($this->removeAccents($name)));
                                $posB = strpos($this->removeAccents($result->getLbNom()),($this->removeAccents($name)));
                            }

                            if($posA !== false) {
                                $temp[] = $result->getNameVern();
                            }
                            if($posB !== false) {
                                $temp[] = $result->getLbNom();
                            }
                        }
                    }

                    $results = $piafRepository->findBy(array('nameVern' => $temp));
                }

                $service = $this->container->get('gsquad_piaf.set_habitat');

                $listPiafsObserves = $service->setHabitats($results);
            }

            return $this->render('search/search.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'form' => $form->createView(),
                'list_piafs' => $listPiafsObserves,
            ]);
        }

        return $this->render('search/search.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
            'list_piafs' => $listPiafsObserves,
        ]);
    }

    /**
     * @Route("/ajax/autocomplete/update/data", name="ajax_autocomplete")
     */
    public function updateDataAction(Request $request)
    {
        $session = $request->getSession();
        $data = $request->get('input');

        if($session->has('results')) {
            $results = $session->get('results');
        } else {
            $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
            $results = $piafRepository->fetchAllNomVernLbNomObservedPiaf();

            $session->set('results', $results);
        }

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

        if($speciesList == '<ul id="matchList">') {
            $speciesList .= '<p>Pas de correspondance</p>';
        }

        $speciesList .= '</ul>';

        $response = new JsonResponse();
        $response->setData(array('speciesList' => $speciesList));
        return $response;
    }

    private function removeAccents($string) {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
    }

    /**
     * @Route("/observations", name="observations")
     */
    public function pageOiseauAction(Request $request)
    {
    }
}
