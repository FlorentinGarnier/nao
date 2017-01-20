<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 20/01/2017
 * Time: 18:06
 */

namespace Gsquad\UserBundle\EventListener;


use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewUserRolesSet implements EventSubscriberInterface
{
    private $um;
    private $em;

    public function __construct(UserManager $um, EntityManager $em)
    {
        $this->um = $um;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => "onRegistrationSuccess"
        );
    }

    public function onRegistrationSuccess(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();
        $user->setRoles(array("ROLE_MEMBRE", "ROLE_UTILISATEUR"));
        $this->um->updateUser($user);
        $this->em->flush();
    }
}
