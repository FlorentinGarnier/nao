<?php
/**
 * Created by PhpStorm.
 * User: florentingarnier
 * Date: 11/11/2016
 * Time: 16:52
 */

namespace Gsquad\UserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadDataUser implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
       $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        //liste des rôles
        $users = [
            'super_admin',
            'admin',
            'chercheur',
            'adherent',
            'membre',
            'utilisateur'
        ];

        //Hydratation des Roles
        foreach ($users as $item) {

            $user = $userManager->createUser();
            $user->setUsername($item);
            $user->setEmail($item . '@' . $item . '.fr');
            $user->setPlainPassword($item);
            $user->setEnabled(true);
            $user->setRoles(['ROLE_' . strtoupper($item)]);

            $userManager->updateUser($user, true);
        }
    }
}