<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Exception\UserException;
use AppBundle\Form\Type\ForgotPasswdEmailType;
use AppBundle\Form\Type\ForgotPasswdType;
use AppBundle\Form\Type\LoginType;
use AppBundle\Form\Type\RegisterType;
use AppBundle\Service\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route(
     *     "/login",
     *     name="login"
     * )
     * @param AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $utils)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('blog_index');
        }
        $error = $utils->getLastAuthenticationError();

        $loginForm = $this->createForm(LoginType::class, array(
            'username' => $utils->getLastUsername()
        ));
        return $this->render('security/login.html.twig', array(
            'loginError' => $error,
            'loginForm' => $loginForm->createView()
        ));
    }

    /**
     * @Route(
     *     "/register",
     *      name="blog_register"
     * )
     * @param Request $request
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserManager $manager)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('blog_index');
        }
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            try {
                $manager->registerManager($user);
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.security.register', [],'message'));
                return $this->redirectToRoute('blog_register');
            } catch (UserException $ex) {
                $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
            }
        }

        return $this->render(':security:register.html.twig', array(
            'registerForm' => $registerForm->createView()
        ));
    }

    /**
     * @Route(
     *     "/activeAccount/{activeToken}",
     *     name="blog_activeAccount"
     * )
     * @param $activeToken
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activeAction($activeToken, UserManager $manager)
    {

        try {
            $manager->activationManager($activeToken);
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.security.activation', [],'message'));
            return $this->redirectToRoute('login');
        } catch (UserException $ex) {
            $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
        }
        return $this->redirectToRoute('login');
    }

    /**
     * @Route(
     *     "/forgot_password",
     *     name="blog_forgot_passwd_email"
     * )
     * @param Request $request
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswdEmailAction(Request $request, UserManager $manager)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('blog_index');
        }
        $form = $this->createForm(ForgotPasswdEmailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $email = $form->get('email')->getData();
                $manager->forgotPasswdEmailManager($email);
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.security.forgotPassword', [],'message'));
                return $this->redirectToRoute('blog_forgot_passwd_email');
            } catch (UserException $ex) {
                $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
            }
        }
        return $this->render(':security:forgotPasswd.html.twig', array(
            'ForgotPasswdEmailForm' => $form->createView(),
        ));
    }

    /**
     * @Route(
     *     "/forgot_password/{activeToken}",
     *     name="blog_forgot_passwd"
     * )
     * @param Request $request
     * @param UserManager $manager
     * @param $activeToken
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswdAction(Request $request, UserManager $manager, $activeToken)
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(array('activeToken' => $activeToken));
        if ($user !== null) {
            $form = $this->createForm(ForgotPasswdType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $manager->forgotPasswdManager($user, $activeToken, $form->get('plainPassword')->getData());
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('success.security.activePassword', [],'message'));

                return $this->redirectToRoute('login');
            }
            return $this->render(':security:forgotPasswd.html.twig', array(
                'forgotPasswdForm' => $form->createView(),
                'activeTooken' => $activeToken
            ));
        }
        $this->get('session')->getFlashBag()->add('error', 'Invalid url');
        return $this->redirectToRoute('login');
    }
}