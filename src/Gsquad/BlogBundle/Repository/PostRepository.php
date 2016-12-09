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
    public function getPublishedPosts()
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->select('p')
            ->where('p.status = :status')
                ->setParameter('status', 'publié')
        ;

        return $query;
    }

    public function getPendingPosts()
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->select('p')
            ->where('p.status = :status')
                ->setParameter('status', 'en attente')
            ;

        return $query
            ->getQuery()
            ->getResult();
    }

    public function getComments($query)
    {
        return $query->leftJoin('p.comments', 'c')
            ->addSelect('c');
    }

    public function orderByDescDate($query)
    {
        return $query->orderBy('p.creationDate', 'DESC');
    }

    public function getAllPosts($currentPage = 1)
    {
        $query = $this->getPublishedPosts();
        $this->getComments($query);
        $this->orderByDescDate($query);

        $query->getQuery();

        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    public function getPostsByCategory($category, $currentPage = 1)
    {
        $query = $this->getPublishedPosts();

        $query->andWhere('p.category = :category')
            ->setParameter('category', $category);

        $this->getComments($query);
        $this->orderByDescDate($query);

        $query->getQuery();

        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    public function getPostsBySearch($search, $currentPage = 1)
    {
        $query = $this->getPublishedPosts();

        $query->andWhere('p.title LIKE :search OR p.content LIKE :search')
            ->setParameter('search', '%' . $search . '%');

        $this->getComments($query);
        $this->orderByDescDate($query);

        $query->getQuery();

        $paginator = $this->paginate($query, $currentPage);

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
        $qb = $this->getPublishedPosts()->select('COUNT(p)');

        return $qb->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function countPublishedTotalByCategory($category)
    {
        $qb = $this->getPublishedPosts();

        $qb->andWhere('p.category = :category')
                ->setParameter('category', $category)
            ->select('COUNT(p)')
        ;

        return $qb->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function countPublishedTotalBySearch($search)
    {
        $qb = $this->getPublishedPosts();

        $qb->andWhere('p.title LIKE :search OR p.content LIKE :search')
                ->setParameter('search', '%' . $search . '%')
            ->select('COUNT(p)');

        return $qb->getQuery()
            ->getSingleScalarResult();
    }
}
