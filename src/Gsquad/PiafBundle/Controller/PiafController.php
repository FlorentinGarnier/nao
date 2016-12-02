<?php

namespace Gsquad\PiafBundle\Controller;

use Gsquad\PiafBundle\Entity\Observation;
use Gsquad\PiafBundle\Entity\Photo;
use Gsquad\PiafBundle\Form\Type\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PiafController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        $listPiafsObserves = [];
        $idObservations = [];
        $choiceEspece = [];
        $latitudes = [];
        $longitudes = [];
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
                    ->add('longitude', NumberType::class, array('required' => false))
                    ->add('range', NumberType::class, array(
                        'required' => false,
                        'attr' => array(
                            'min' => 5,
                            'max' => 50
                        )
                    ));
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
                $latitude = false;
                $longitude = false;
                $range = false;
            }
            else {
                $name = $form["nameVern"]->getData();
                $departement = $form["departement"]->getData();
                $espece = $form["espece"]->getData();
                if($form->has("latitude")) {
                    $latitude = $form["latitude"]->getData();
                } else {
                    $latitude = false;
                }
                if($form->has("longitude")) {
                    $longitude = $form["longitude"]->getData();
                } else {
                    $longitude = false;
                }
                if($form->has("range")) {
                    $range = $form["range"]->getData();
                } else {
                    $range = false;
                }
            }

            $listPiafsObserves = [];

            if($latitude || $longitude) {
                $listObservations = $observationRepository
                    ->findObservationByPosition($latitude, $longitude, $range);

                foreach ($listObservations as $observation) {
                    $piafTemp = $observation->getPiaf();

                    if($espece) {
                        if($observation->getLatitude() && $observation->getLongitude() && ($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ")) {
                            $latitudes[] = $observation->getLatitude();
                            $longitudes[] = $observation->getLongitude();
                        }
                    }
                    else {
                        if($observation->getLatitude()
                            && $observation->getLongitude()
                            && (
                                $name == null
                                || strpos($this->removeAccents($piafTemp->getLbNom()),($this->removeAccents($name))) !== false
                                || strpos($this->removeAccents($piafTemp->getNameVern()),($this->removeAccents($name))) !== false
                            )
                        ) {
                            $latitudes[] = $observation->getLatitude();
                            $longitudes[] = $observation->getLongitude();
                        }
                    }

                    $posA = true;
                    $posB = true;

                    if($espece) {
                        if($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ") {
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
            elseif($departement) {
                $listObservations = $observationRepository
                    ->findBy(
                        array('departement' => $departement)
                    );

                foreach ($listObservations as $observation) {
                    $piafTemp = $observation->getPiaf();

                    if($espece) {
                        if($observation->getLatitude() && $observation->getLongitude() && ($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ")) {
                            $latitudes[] = $observation->getLatitude();
                            $longitudes[] = $observation->getLongitude();
                        }
                    }
                    else {
                        if($observation->getLatitude()
                            && $observation->getLongitude()
                            && (
                                $name == null
                                ||strpos($this->removeAccents($piafTemp->getLbNom()),($this->removeAccents($name)))
                                || strpos($this->removeAccents($piafTemp->getNameVern()),($this->removeAccents($name)))
                            )
                        ) {
                            $latitudes[] = $observation->getLatitude();
                            $longitudes[] = $observation->getLongitude();
                        }
                    }

                    $posA = true;
                    $posB = true;

                    if($espece) {
                        if($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ") {
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

                $listObservations = $observationRepository
                    ->findAll();

                if($espece) {
                    foreach ($listObservations as $observation) {
                        $piafTemp = $observation->getPiaf();

                        if(
                            $observation->getLatitude() &&
                            $observation->getLongitude() &&
                            ($piafTemp->getNameVern() === $espece || $piafTemp->getNameVern() === $espece." ")
                        ) {
                            $latitudes[] = $observation->getLatitude();
                            $longitudes[] = $observation->getLongitude();
                        }
                    }
                    $results = $piafRepository->findObservedPiafBy($espece);
                }
                else {
                    foreach ($listObservations as $observation) {
                        $piafTemp = $observation->getPiaf();

                        if(
                            $observation->getLatitude() &&
                            $observation->getLongitude() &&
                            ($piafTemp->getNameVern() === $name || $piafTemp->getNameVern() === $name." ")
                        ) {
                            $latitudes[] = $observation->getLatitude();
                            $longitudes[] = $observation->getLongitude();
                        }
                    }
                    $results = $piafRepository->findObservedPiafBy($name);
                }

                if(empty($results)) {
                    $results = $piafRepository->findAllObservedPiaf();
                    $listObservations = $observationRepository->findAll();

                    //On recupere les coordonnees d'observations correspondant a la saisie
                    foreach ($listObservations as $observation) {
                        if($name === null) {
                            if(
                                $observation->getLatitude() &&
                                $observation->getLongitude()
                            ) {
                                $latitudes[] = $observation->getLatitude();
                                $longitudes[] = $observation->getLongitude();
                            }
                        } else {
                            $posA = strpos($this->removeAccents($observation->getPiaf()->getNameVern()),($this->removeAccents($name)));
                            $posB = strpos($this->removeAccents($observation->getPiaf()->getLbNom()),($this->removeAccents($name)));

                            if(($posA !== false || $posB !== false) && ($observation->getLatitude() && $observation->getLongitude())) {
                                $latitudes[] = $observation->getLatitude();
                                $longitudes[] = $observation->getLongitude();
                            }
                        }
                    }

                    //On recupere les attributs des piafs correspondant a la saisie
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

                    if(count($temp) > 0) {
                        $results = $piafRepository->fetchAllWith($temp);
                    }
                    else {
                        $results = [];
                    }
                }

                $service = $this->container->get('gsquad_piaf.set_habitat');

                $listPiafsObserves = $service->setHabitats($results);
            }

            //Modification des coordonnees si besoin
            if($this->getUser() == null
                || in_array('ROLE_UTILISATEUR', $this->getUser()->getRoles())
                || in_array('ROLE_MEMBRE', $this->getUser()->getRoles())
            ) {
                $service = $this->container->get('gsquad_piaf.modifier_coordonnees');

                $latitudes = $service->changeLatitudes($latitudes);
                $longitudes = $service->changeLongitudes($longitudes);
            }
            elseif(in_array('ROLE_ADHERENT', $this->getUser()->getRoles())) {
                $service = $this->container->get('gsquad_piaf.modifier_coordonnees');

                $latitudes = $service->changeLatitudesAdherent($latitudes);
                $longitudes = $service->changeLongitudesAdherent($longitudes);
            }

            foreach ($listObservations as $observation) {
                $idObservations[] = $observation->getId();
            }

            $session = $request->getSession();
            $session->set('obs', $idObservations);

            return $this->render('search/search.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'form' => $form->createView(),
                'list_piafs' => $listPiafsObserves,
                'latitudes' => $latitudes,
                'longitudes' => $longitudes,
            ]);
        }

        return $this->render('search/search.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
            'list_piafs' => $listPiafsObserves,
            'latitudes' => $latitudes,
            'longitudes' => $longitudes,
        ]);
    }

    /**
     * @Route("/ajax/autocomplete/update/data", name="ajax_autocomplete")
     */
    public function updateDataAction(Request $request)
    {
        $data = $request->get('input');

        $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
        $results = $piafRepository->fetchAllNomVernLbNomObservedPiaf();

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
     * @Route("/observations/{id}", name="observations")
     */
    public function pageOiseauAction(Request $request)
    {
        $session = $request->getSession();
        $id = $request->get('id');
        $idObservations = $session->get('obs');
        $listFinal = [];

        $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
        $observationRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Observation');

        $piaf = $piafRepository->find($id);
        $listObservations = $observationRepository->findBy(array('id' => $idObservations));

        foreach($listObservations as $observation) {
            if($observation->getPiaf() == $piaf) {
                $listFinal[] = $observation;
            }
        }

        $service = $this->container->get('gsquad_piaf.set_habitat');

        $piaf = $service->setHabitats([$piaf])[0];

        return $this->render('search/observations.html.twig', [
            "piaf" => $piaf,
            "observations" => $listFinal
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutObservationAction(Request $request)
    {
        $service = $this->container->get('gsquad_piaf.get_departements');
        $depts = $service->getDepartementsArray();
        $choiceEspece['Autre'] = false;

        $session = $request->getSession();

        if($session->has('list')) {
            $listEspeces = $session->get('list');
        } else {
            $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
            $listEspeces = $piafRepository->findAll();

            $session->set('list', $listEspeces);
        }

        foreach($listEspeces as $espece) {
            if($espece->getNameVern() != null && !array_key_exists($espece->getNameVern(), $choiceEspece)) {
                $choiceEspece[$espece->getNameVern()] = $espece->getId();
            }
        }

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('image', FileType::class, array('required' => false), array('label' => 'Photo / image :'))
            ->add('latitude', NumberType::class, array('required' => false), array('label' => 'Latitude :'))
            ->add('longitude', NumberType::class, array('required' => false), array('label' => 'Longitude :'))
            ->add('observateur', TextType::class, array('required' => false), array('label' => 'Nom de l\'observateur :'))
            ->add('city', TextType::class, array('label' => 'Commune associée à l\'observation :'))
            ->add('dateObservation', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker']
            ))
            ->add('departement', ChoiceType::class,
                array('choices' => $depts), array('label' => 'Département associé à l\'observation :'))
            ->add('espece', ChoiceType::class,
                array('choices' => $choiceEspece), array('label' => 'Espèce observée :'))
            ->add('espece-autre', TextType::class, array('label' => 'Autre :'))
            ->add('submit',SubmitType::class, array('label' => 'Valider l\'observation'))
            ->getForm();

        if($form->handleRequest($request)->isValid())
        {
            $obs = new Observation();
            $obs->setLatitude($form["latitude"]->getData());
            $obs->setLongitude($form["longitude"]->getData());
            $obs->setObservateur($form["observateur"]->getData());
            $obs->setCity($form["city"]->getData());
            $obs->setDateObservation($form["dateObservation"]->getData());
            $obs->setDepartement($form["departement"]->getData());

            $piafRepository = $this->getDoctrine()->getRepository('GsquadPiafBundle:Piaf');
            $piaf = $piafRepository->find($form["espece"]->getData());

            $obs->setPiaf($piaf);

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form["image"]->getData();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $image = new Photo();
            $image->setImgUrl($fileName);

            $em = $this->getDoctrine()->getManager();

            $em->persist($image);
            $em->flush();

            $obs->setPhoto($image);

            $em->persist($obs);
            $em->flush();

            return $this->render('search/nouvellesaisie.html.twig', [
                "form" => $form->createView(),
                "observation" => $obs
            ]);
        }

        return $this->render('search/nouvellesaisie.html.twig', [
            "form" => $form->createView(),
            "observation" => null
        ]);
    }
}
