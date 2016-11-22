<?php

namespace Gsquad\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    public function getAllPosts($currentPage = 1)
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->select('p')
                ->where('p.status = :status')
                ->setParameter('status', 'publié')
            ->leftJoin('p.comments', 'c')
              ->addSelect('c')
            ->orderBy('p.creationDate', 'DESC')
            ->getQuery();


        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    public function getPostsByCategory($category, $currentPage = 1)
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->select('p')
            ->where('p.category = :category')
                ->setParameter('category', $category)
            ->andWhere('p.status = :status')
                ->setParameter('status', 'publié')
            ->leftJoin('p.comments', 'c')
                ->addSelect('c')
            ->orderBy('p.creationDate', 'DESC')
            ->getQuery();
        dump($query);
        $paginator = $this->paginate($query, $currentPage);
        dump($paginator);
        return $paginator;
    }

    public function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page -1))
            ->setMaxResults($limit)
        ;

        return $paginator;
    }

    public function countPublishedTotal()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->setParameter('status', 'publié')
            ->select('COUNT(p)')
        ;

        return $qb->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function countPublishedTotalByCategory($category)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.status = :status')
                ->setParameter('status', 'publié')
            ->andWhere('p.category = :category')
                ->setParameter('category', $category)
            ->select('COUNT(p)')
        ;

        return $qb->getQuery()
            ->getSingleScalarResult()
            ;
    }

}
