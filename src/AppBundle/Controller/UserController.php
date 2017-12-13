<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-16
 * Time: 11:06
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\Type\ChangeEmailType;
use AppBundle\Form\Type\ChangePasswordType;
use AppBundle\Form\Type\SearchMainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{

    protected $limit = 10;

    /**
     * @Route(
     *     "/account/settings/{page}",
     *     name="account_settings",
     *     defaults={ "page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountSettingsAction($page)
    {
        $user = $this->getUser();
        $author = $user->getId();
        $pagination = $this->getPagination(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'order' => 'DESC',
            'author' => $author
        ), $page, $this->limit);

        return $this->render(':user:account_settings.html.twig', array(
            'user' => $user,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route(
     *     "/account/change-password",
     *     name="account_change_password"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordEncoder = $this->get('security.password_encoder');
            $password = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.user.password', [], 'message'));

            return $this->redirectToRoute('account_change_password');
        }

        return $this->render(':user:account_editing.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'title' => 'Change password'
        ));
    }

    /**
     * @Route(
     *     "/account/change-email",
     *     name="account_change_email"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changeEmailAction(Request $request)
    {

        $user = $this->getUser();
        $form = $this->createForm(ChangeEmailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($form->get('newEmail')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.user.email', [], 'message'));

            return $this->redirectToRoute('account_change_email');
        }

        return $this->render(':user:account_editing.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'title' => 'Change email'
        ));
    }

    /**
     * @Route(
     *     "/admin/users/{page}",
     *     name="admin_user",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     *)
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminUserAction($page)
    {
        $pagination = $this->getPaginator($page, $this->limit, User::class);
        return $this->render('user/admin-user.html.twig', array(
            'title' => 'Users list',
            'userAdmin' => $pagination
        ));
    }

    /**
     * @Route(
     *     "/admin/active/{id}",
     *     name="admin_user_active",
     *     requirements={"id" = "\d+"}
     * )
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableUserAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        if ($user->getIsActive() == 1) {
            $user->setIsActive(0);
        } else {
            $user->setIsActive(1);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.user.active', [], 'message'));

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route(
     *     "/admin/search-user/{page}",
     *     name="admin_search_user",
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
        $repository = $this->getDoctrine()->getRepository(User::class);
        $qb = $repository->searchUser($searchParam);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $this->render('user/admin-user.html.twig', array(
            'title' => sprintf('search %s', $searchParam),
            'userAdmin' => $pagination,
            'searchParam' => $searchParam
        ));
    }
    public function searchFormAction(){
        $form = $this->createForm(SearchMainType::class,[], ['action' => $this->generateUrl('admin_search_user')]);
        return $this->render(':Template:searchForm.html.twig',array(
            'form' => $form->createView(),
        ));
    }

}