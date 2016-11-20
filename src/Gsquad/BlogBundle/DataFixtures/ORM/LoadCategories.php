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

class LoadCategories implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listCategories = array(
            'Catégorie 1', 'Catégorie 2', 'Catégorie 3'
        );

        foreach ($listCategories as $category){
            $newCategory = new Category();
            $newCategory->setName($category);

            $manager->persist($newCategory);
        }
        $manager->flush();
    }
}