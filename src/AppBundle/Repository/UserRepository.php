<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-09
 * Time: 18:26
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Count;

class UserRepository extends EntityRepository
{
    public function ConutUser(){

        $qb = $this->createQueryBuilder('u');
        $qb->select('COUNT(u.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchUser($searchParam)
    {
        $search = '%'.$searchParam.'%';
        $qb = $this->createQueryBuilder('u');
        $qb->select('u')
            ->where('u.username LIKE :search')
            ->setParameter('search', $search);

        return $qb;
    }
}