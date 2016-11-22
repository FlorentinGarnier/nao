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

    const ROLES = [
        'super_admin',
        'admin',
        'chercheur',
        'adherent',
        'membre',
        'utilisateur'
    ];

    public function setContainer(ContainerInterface $container = null)
    {
       $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        //liste des rôles


        //Hydratation des Roles
        foreach ( self::ROLES as $role) {

            $user = $userManager->createUser();
            $user->setFirstName($role);
            $user->setLastName($role);
            $user->setUsername($role);
            $user->setBirthday(new \DateTime('NOW'));
            $user->setEmail($role . '@' . $role . '.fr');
            $user->setPlainPassword($role);
            $user->setEnabled(true);
            $user->setRoles(['ROLE_' . strtoupper($role)]);
            $user->setAdress1('fgdfg');
            $user->setZipCode('63570');

            $userManager->updateUser($user, true);
        }
    }
}