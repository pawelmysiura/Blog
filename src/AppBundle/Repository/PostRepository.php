<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;

class PostRepository extends EntityRepository
{
    public function getQueryBuilder(array $params = array())
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p, t, c, a')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.author', 'a');

        if (!empty($params['status'])) {
            if ('published' == $params['status']) {
                $qb->where('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                    ->setParameter('currDate', new \DateTime());
            } elseif ('unpublished' == $params['status']) {
                $qb->where('p.publishedDate > :currDate OR p.publishedDate IS NULL')
                    ->setParameter('currDate', new \DateTime());
            }
        }
        if (!empty($params['orderBy'])) {
            if (!empty($params['order'])) {
                $order = $params['order'];
            } else {
                $order = null;
            }
            $qb->orderBy($params['orderBy'], $order);
        }
        if (!empty($params['categorySlug'])) {
            $qb->andwhere('c.slug = :categorySlug')
                ->setParameter('categorySlug', $params['categorySlug']);
        }
        if (!empty($params['tagSlug'])) {
            $qb->andWhere('t.slug = :tagSlug')
                ->setParameter('tagSlug', $params['tagSlug']);
        }
        if (!empty($params['year']) && !empty($params['month'])) {
            $year = $params['year'];
            $month = $params['month'];
            $date = new \DateTime("{$year}-{$month}-01");
            $qb->andwhere('p.publishedDate BETWEEN :start AND :end')
                ->setParameter('start', $date->format('Y-m-d'))
                ->setParameter('end', $date->format('Y-m-t'));
        }
        if (!empty($params['author'])){
            $qb->andwhere('p.author = :author')
                ->setParameter('author', $params['author']);
        }
        if (!empty($params['searchParam'])){
            $searchParam = '%'.$params['searchParam'].'%';
            $qb->andWhere('p.title LIKE :searchParam OR p.content LIKE :searchParam')
                ->setParameter('searchParam', $searchParam);
        }


        return $qb;
    }

    public function postBuilder($slug)
    {
        $qb = $this->getQueryBuilder(array(
            'status' => 'published'
        ));
        $qb->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getLastPublished($limit)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.title, p.content, p.slug, p.publishedDate')
            ->where('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
            ->setParameter('currDate', new \DateTime())
            ->orderBy('p.publishedDate', 'DESC')
            ->setMaxResults($limit);
        return $qb->getQuery()->getArrayResult();
    }

    public function ConutPost(array $params = array()){

        $qb = $this->createQueryBuilder('p');
        $qb->select('COUNT(p.id)');

        if ('published' == $params['status']){
            $qb->where('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                ->setParameter('currDate', new \DateTime());
        } elseif ('unpublished' == $params['status']){
            $qb->where('p.publishedDate > :currDate OR p.publishedDate IS NULL')
                ->setParameter('currDate', new \DateTime());
        }


        return $qb->getQuery()->getSingleScalarResult();
    }


    public function deleteCategory($deletCategory, $new){
        $qb = $this->createQueryBuilder('p');
        $qb->update()
            ->set('p.category', ':new')
            ->where('p.category = :deletCategory')
            ->setParameters(array(
                'deletCategory' => $deletCategory,
                'new' => $new
            ))
            ->getQuery()
            ->execute();
    }


}