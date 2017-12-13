<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use AppBundle\Form\Type\CreateType;
use AppBundle\Form\Type\SearchMainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TagController extends BaseController
{
    protected $limit = 10;

    /**
     * @Route(
     *     "/admin/tag/{page}",
     *     name="admin_tag",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminTagAction($page)
    {
        $pagination = $this->getPaginator($page, $this->limit, Tag::class);
        return $this->render('tag/admin-tag.html.twig', array(
            'title' => 'Tags list',
            'tagAdmin' => $pagination
        ));
    }

    /**
     * @Route(
     *     "/admin/delete-tag/{id}",
     *     name="delete_tag",
     *     requirements={"id" = "\d+"}
     * )
     * @Method("GET")
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"id": "id"}})
     * @param Tag $tag
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Tag $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.tag.delete', [], 'message'));

        return $this->redirectToRoute('admin_tag');
    }

    /**
     * @Route(
     *     "/admin/tag/edit/{id}",
     *     name="tag_edit",
     *     defaults={"id" = NULL},
     *     requirements={"id" = "\d+"}
     * )
     * @Method({"GET", "POST"})
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"id": "id"}})
     * @param Tag $tag
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $tag)
    {
        $form = $this->createForm(CreateType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setName($form->get('name')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.tag.edit', [], 'message'));
            return $this->redirectToRoute('admin_tag');
        }

        return $this->render('tag/admin-create.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Category'
        ));
    }

    /**
     * @Route(
     *     "/admin/tag/create",
     *     name="tag_create"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $tag = new Tag();
        $form = $this->createForm(CreateType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setName($form->get('name')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('success.tag.create', [], 'message'));
            return $this->redirectToRoute('admin_tag');
        }

        return $this->render('tag/admin-create.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Category'
        ));
    }

    /**
     * @Route(
     *     "/admin/search-tag/{page}",
     *     name="admin_search_tag",
     *     defaults={"page" = 1},
     *     requirements={"page" = "/d+"}
     * )
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, $page)
    {
        $form = $request->request->get('search_main');
        $searchParam = $form['search'];
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $qb = $repository->searchTag($searchParam);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $this->render('tag/admin-tag.html.twig', array(
            'title' => sprintf('search %s', $searchParam),
            'tagAdmin' => $pagination,
            'searchParam' => $searchParam
        ));
    }

    public function searchFormAction(){
        $form = $this->createForm(SearchMainType::class,[], ['action' => $this->generateUrl('admin_search_tag')]);
        return $this->render(':Template:searchForm.html.twig',array(
            'form' => $form->createView(),
        ));
    }
}