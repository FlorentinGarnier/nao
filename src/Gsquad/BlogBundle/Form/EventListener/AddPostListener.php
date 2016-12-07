<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 07/12/2016
 * Time: 10:58
 */

namespace Gsquad\BlogBundle\Form\EventListener;


use Doctrine\ORM\EntityManager;
use Gsquad\BlogBundle\Entity\Category;
use Gsquad\Utils\Slugger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddPostListener implements EventSubscriberInterface
{
    private $em;
    private $slugger;

    public function __construct(EntityManager $em, Slugger $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'onPreSubmit'
        );
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if (!$data) {
            return;
        }

        $category = $data['category'];

        // Si la catégorie existe déjà, on ne fait rien
        if ($this->em->getRepository(Category::class)->find($category)) {
            return;
        }

        // Sinon, on crée la nouvelle catégorie
        $newCategory = new Category();
        $newCategory->setName($category);
        $slug = $this->slugger->slugify($category);
        $newCategory->setSlug($slug);
        $this->em->persist($newCategory);
        $this->em->flush();

        $data['category'] = $newCategory->getId();
        $event->setData($data);
    }
}