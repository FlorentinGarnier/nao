<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 11/11/2016
 * Time: 08:31
 */

namespace Gsquad\BlogBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gsquad\BlogBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCategories implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $listCategories = array(
            'Catégorie 1', 'Catégorie 2', 'Catégorie 3'
        );

        foreach ($listCategories as $category){
            $newCategory = new Category();
            $newCategory->setName($category);

            // Appel service Slugger pour générer le slug de la catégorie
            $slugger = $this->container->get('gsquad_blog.slugger');
            $slug = $slugger->slugify($category);
            $newCategory->setSlug($slug);

            $manager->persist($newCategory);
        }
        $manager->flush();
    }
}
