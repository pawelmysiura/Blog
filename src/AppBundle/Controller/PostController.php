<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-05
 * Time: 14:03
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use AppBundle\Form\Type\SearchMainType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends BaseController
{
    protected $limit = 5;

    /**
     * @Route(
     *     "/news/{page}",
     *     name="blog_news",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction($page)
    {
        $pagination = $this->getPagination(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'order' => 'DESC'
        ), $page, $this->limit);
        return $this->render('post/posts.html.twig', array(
            'pagination' => $pagination,
            'pageTitle' => 'News'
        ));
    }

    /**
     * @Route (
     *     "/category/{slug}/{page}",
     *      name="blog_category",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($slug, $page)
    {
        $pagination = $this->getPagination(array(
            'status' => 'published',
            'orderBY' => 'p.publishedDate',
            'order' => 'DESC',
            'categorySlug' => $slug
        ), $page, $this->limit);

        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
        $category = $repository->findOneBy(array('slug' => $slug));
        return $this->render('post/posts.html.twig', array(
            'pagination' => $pagination,
            'pageTitle' => $category->getName()
        ));
    }

    /**
     * @Route(
     *     "/tag/{slug}/{page}",
     *      name="blog_tags",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagAction($slug, $page)
    {
        $pagination = $this->getPagination(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'order' => 'DESC',
            'tagSlug' => $slug
        ), $page, $this->limit);
        $repository = $this->getDoctrine()->getRepository('AppBundle:Tag');
        $tag = $repository->findOneBy(array('slug' => $slug));
        return $this->render('post/posts.html.twig', array(
            'pagination' => $pagination,
            'pageTitle' => $tag->getName()
        ));
    }

    /**
     * @Route(
     *     "/{slug}",
     *     name="blog_post",
     * )
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction($slug)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $post = $repository->postBuilder($slug);

        if ($post == null)
            throw $this->createNotFoundException('Post not found');

        return $this->render('post/post.html.twig', array(
            'post' => $post
        ));
    }

    /**
     * @Route(
     *     "/archive/{year}/{month}/{page}",
     *     name="blog_archive",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $year
     * @param $month
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function archiveAction($year, $month, $page)
    {
        $pagination = $this->getPagination(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'order' => 'DESC',
            'year' => $year,
            'month' => $month
        ), $page, $this->limit);
        return $this->render('post/posts.html.twig', array(
            'pagination' => $pagination,
            'pageTitle' => 'Archive'
        ));
    }

    /**
     * @Route(
     *     "/admin/posts/{page}",
     *     name="admin_post",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminPostAction($page)
    {
        $pagination = $this->getPagination(array(
            'OrderBy' => 'p.createdDate',
            'order' => 'DESC'
        ), $page, $this->limit);

        $title = $this->get('translator')->trans('post.site_name.admin_post', [], 'controller');

        return $this->render('post/admin-post.html.twig', array(
            'title' => $title,
            'postAdmin' => $pagination
        ));
    }

    /**
     * @Route(
     *     "/admin/post/edit/{id}",
     *     name="post_edit",
     *     defaults={"id" = NULL},
     *     requirements={"id" = "\d+"}
     * )
     * @Method({"GET", "POST"})
     * @ParamConverter("post", class="AppBundle:Post", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $post)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.post.edit', [],'message'));

            return $this->redirectToRoute('admin_post', array(
                'id' => $post->getId()
            ));
        }

        return $this->render('post/admin-create-post.html.twig', array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }

    /**
     * @Route(
     *     "/admin/post/create",
     *     name="post_create"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreateDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.post.create', [],'message'));

            return $this->redirectToRoute('admin_post', array(
                'id' => $post->getId()
            ));
        }

        return $this->render('post/admin-create-post.html.twig', array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }

    /**
     * @Route(
     *     "/admin/delete-post/{id}",
     *     name="delete_post",
     *     requirements={"id" = "\d+"}
     * )
     * @Method("GET")
     * @ParamConverter("post", class="AppBundle:Post", options={"mapping": {"id": "id"}})
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($post)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.post.delete', [],'message'));

        return $this->redirectToRoute('admin_post');
    }

    /**
     * @Route(
     *     "/admin/search/{page}",
     *     name="admin_search",
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
        $pagination = $this->getPagination(array(
            'OrderBy' => 'p.createdDate',
            'order' => 'DESC',
            'searchParam' => $searchParam
        ), $page, $this->limit);
        return $this->render('post/admin-post.html.twig', array(
            'title' => sprintf('search %s', $searchParam),
            'postAdmin' => $pagination,
            'searchParam' => $searchParam
        ));
    }
    public function searchFormAction(){
        $form = $this->createForm(SearchMainType::class,[], ['action' => $this->generateUrl('admin_search')]);
        return $this->render(':Template:searchForm.html.twig',array(
            'form' => $form->createView()
        ));
    }
}