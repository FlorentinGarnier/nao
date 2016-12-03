<?php

namespace Gsquad\PiafBundle\Repository;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLatestObs($limit)
    {
        $query = $this->createQueryBuilder('obs')
            ->select('obs')
            ->where('obs.valid = :isValid')
                ->setParameter('isValid', true)
            ->orderBy('obs.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}
