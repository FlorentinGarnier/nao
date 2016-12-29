<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 29/12/2016
 * Time: 10:40
 */

namespace Gsquad\UserBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUsersByRole($role)
    {
        $query = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.roles LIKE :role')
                ->setParameter('role', '%' . $role . '%');

        return $query
            ->getQuery()
            ->getResult();
    }
}
