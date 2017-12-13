<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
class CategoryRepository extends EntityRepository
{
    public function ConutCategory(){

        $qb = $this->createQueryBuilder('c');
        $qb->select('COUNT(c.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchCategory($searchParam)
    {
        $search = '%'.$searchParam.'%';
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
            ->where('c.name LIKE :search')
            ->setParameter('search', $search);

        return $qb;
    }
}