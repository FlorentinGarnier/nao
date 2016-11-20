<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 11/11/2016
 * Time: 09:01
 */

namespace Gsquad\BlogBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gsquad\BlogBundle\Entity\Tag;

class LoadTags implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listTags = array(
            'piaf', 'observation', 'instrument'
        );

        foreach ($listTags as $tag){
            $newTag = new Tag();
            $newTag->setTitle($tag);
            $manager->persist($newTag);
        }
        $manager->flush();
    }
}
