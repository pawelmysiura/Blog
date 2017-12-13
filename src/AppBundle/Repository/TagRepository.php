<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
class TagRepository extends EntityRepository
{
    public function ConutTag(){

        $qb = $this->createQueryBuilder('t');
        $qb->select('COUNT(t.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchTag($searchParam)
    {
        $search = '%'.$searchParam.'%';
        $qb = $this->createQueryBuilder('t');
        $qb->select('t')
            ->where('t.name LIKE :search')
            ->setParameter('search', $search);

        return $qb;
    }
}