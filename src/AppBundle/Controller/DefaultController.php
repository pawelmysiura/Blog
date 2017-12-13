<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactType;
use AppBundle\Service\Mailer\UserMailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Manager\AdminManager;

class DefaultController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="blog_index"
     * )
     */
    public function indexAction(){
        return ($this->render('default/index.html.twig'));
    }
    /**
     * @Route(
     *     "/about",
     *     name="blog_about"
     * )
     */
    public function aboutAction(){
        return ($this->render('default/about.html.twig'));
    }

    /**
     * @Route(
     *     "/contact",
     *     name="blog_contact"
     * )
     * @param Request $request
     * @param UserMailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request, UserMailer $mailer){

        $form = $this->createForm(ContactType::class);
        if ($request->isMethod('POST')){
        $form->handleRequest($request);
            if ($form->isValid()){
                $formSendTo = $this->container->getParameter('mailer_user');
                $formSendFrom = $this->container->getParameter('mailer_user');
                $formBody = $this->renderView(':email:contact.html.twig', array(
                    'email' => $form->get('email')->getData(),
                    'subject' => $form->get('subject')->getData(),
                    'message' => $form->get('message')->getData()
                ));
                $mailer->sendContactMailer($form->get('email')->getData(), $form->get('subject')->getData(), $formBody);
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.default.send', [],'message'));
                return $this->redirect($this->generateUrl('blog_contact'));
            }
        }
        return $this->render('default/contact.html.twig', array(
            'contactForm' => $form->createView(),
        ));
    }

    /**
     * @Route(
     *     "/admin/",
     *     name="admin_panel"
     * )
     * @param AdminManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function panelAction(AdminManager $manager)
    {
        $coutMainAdmin = $manager->countMainPage();
        return $this->render('default/admin-panel.html.twig', $coutMainAdmin);
    }

}
