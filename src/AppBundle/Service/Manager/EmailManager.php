<?php

namespace AppBundle\Service\Manager;


use AppBundle\Entity\User;
use AppBundle\Service\Mailer\UserMailer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface as template;

class EmailManager
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var template
     */
    private $template;

    /**
     * @var UserMailer
     */
    private $userMailer;

    /**
     * EmailManager constructor.
     * @param Router $router
     * @param template $template
     * @param UserMailer $userMailer
     */
    public function __construct(Router $router, template $template, UserMailer $userMailer)
    {
        $this->router = $router;
        $this->template = $template;
        $this->userMailer = $userMailer;
    }

    public function registerMail(User $user, $activeToken){
        $generatedUrl = $this->router->generate('blog_activeAccount', array(
            'activeToken' => $activeToken
        ), UrlGeneratorInterface::ABSOLUTE_URL);
        $bodyMail = $this->template->render(':email:activeAccount.html.twig', array(
            'generatedUrl' => $generatedUrl
        ));

        $this->userMailer->userMailer($user, 'Activation link', $bodyMail);
    }

    public function forgotPasswdMail(User $user, $token){
        $generatedUrl = $this->router->generate('blog_forgot_passwd', array(
            'activeToken' => $token
        ), UrlGeneratorInterface::ABSOLUTE_URL);
        $bodyMail = $this->template->render(':email:forgotPasswd.html.twig', array(
            'generatedUrl' => $generatedUrl
        ));

        $this->userMailer->userMailer($user, 'Forgot password link', $bodyMail);
        return true;
    }

}