<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Post;;
use AppBundle\Form\Type\CreateType;
use AppBundle\Form\Type\SearchMainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends BaseController
{
    protected $limit = 10;


    /**
     * @Route(
     *     "/admin/categories{page}",
     *     name="admin_category",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminCategoryAction($page)
    {
        $pagination = $this->getPaginator($page, $this->limit, Category::class);
        return $this->render('category/admin-category.html.twig', array(
            'title' => 'Category list',
            'categoryAdmin' => $pagination
        ));
    }

    /**
     * @Route(
     *     "/admin/delete-category/{id}",
     *     name="delete_category",
     *     requirements={"id" = "\d+"}
     * )
     * @Method("GET")
     * @ParamConverter("category", class="AppBundle:Category", options={"mapping": {"id": "id"}})
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($category)
    {

        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $postRepo->deleteCategory($category->getId(), null);

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.category.delete', [], 'message'));

        return $this->redirectToRoute('admin_category');
    }

    /**
     * @Route(
     *     "/admin/category/edit/{id}",
     *     name="category_edit",
     *     defaults={"id" = NULL},
     *     requirements={"id" = "\d+"}
     * )
     * @Method({"GET", "POST"})
     * @ParamConverter("category", class="AppBundle:Category", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Category $category
     */
    public function editAction(Request $request, $category)
    {

        $form = $this->createForm(CreateType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setName($form->get('name')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.category.edit', [], 'message'));
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('category/admin-create.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Category'
        ));
    }

    /**
     * @Route(
     *     "/admin/category/create",
     *     name="category_create"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CreateType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setName($form->get('name')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.category.create', [], 'message'));
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('category/admin-create.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Category'
        ));
    }

    /**
     * @Route(
     *     "/admin/search-category/{page}",
     *     name="admin_search_category",
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
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $qb = $repository->searchCategory($searchParam);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $this->render('category/admin-category.html.twig', array(
            'title' => sprintf('search %s', $searchParam),
            'categoryAdmin' => $pagination,
            'searchParam' => $searchParam
        ));
    }
    public function searchFormAction(){
        $form = $this->createForm(SearchMainType::class,[], ['action' => $this->generateUrl('admin_search_category')]);
        return $this->render(':Template:searchForm.html.twig',array(
            'form' => $form->createView()
        ));
    }
}