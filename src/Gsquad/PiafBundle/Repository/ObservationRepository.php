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

    public function findNotValidObs()
    {
        $query = $this->createQueryBuilder('obs')
            ->select('obs')
            ->where('obs.valid = :isValid')
                ->setParameter('isValid', false)
            ->orderBy('obs.createdAt', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findObservationByPosition($latitude, $longitude, $range) {
        $latMin = $latitude - $range;
        $latMax = $latitude + $range;
        $lonMin = $longitude - $range;
        $lonMax = $longitude + $range;

        $query = $this->createQueryBuilder('o')
            ->where('o.valid = :isValid')
            ->setParameter('isValid', true)
            ->andWhere('o.latitude >= :latmin')
            ->andWhere('o.latitude <= :latmax')
            ->andWhere('o.longitude >= :lonmin')
            ->andWhere('o.longitude <= :lonmax')
            ->setParameter('latmin', $latMin)
            ->setParameter('latmax', $latMax)
            ->setParameter('lonmin', $lonMin)
            ->setParameter('lonmax', $lonMax)
            ->getQuery();

        return $query->getResult();
    }
}
