<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-23
 * Time: 23:11
 */

namespace AppBundle\Controller;


use AppBundle\Form\Type\CreateType;
use AppBundle\Form\Type\SearchMainType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    public function getPaginator($page, $limit, $class)
    {
        $repository = $this->getDoctrine()->getRepository($class);
        $qb = $repository->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);

        return $pagination;
    }

    public function getPagination(array $params = array(), $page, $limit)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $qb = $repository->getQueryBuilder($params);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);

        return $pagination;
    }



}