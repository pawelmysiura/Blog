<?php

namespace AppBundle\Service\Manager;


use AppBundle\Entity\User;
use AppBundle\Exception\UserException;
use AppBundle\Service\Mailer\UserMailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Doctrine\Bundle\DoctrineBundle\Registry as doctrine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Templating\EngineInterface as template;
use Symfony\Component\Translation\Translator;

class UserManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

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
     * @var Translator
     */
    private $translator;
    /**
     * UserManager constructor.
     * @param doctrine $doctrine
     * @param UserPasswordEncoder $encoder
     * @param Router $router
     * @param template $template
     * @param UserMailer $userMailer
     * @param Translator $translator
     */
    public function __construct(doctrine $doctrine, UserPasswordEncoder $encoder, Router $router, template $template, UserMailer $userMailer, Translator $translator)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
        $this->router = $router;
        $this->template = $template;
        $this->userMailer = $userMailer;
        $this->translator = $translator;
    }


    public function generateActiveToken()
    {
        $random = random_bytes(30);

        return base64_encode(bin2hex($random));
    }

    public function registerManager(User $user)
    {

        if (null !== $user->getId()) {
            throw new UserException($this->translator->trans('user.username_exist', [], 'exception'));
        }
        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setActiveToken($this->generateActiveToken());
        $user->setIsActive(false);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        $generatedUrl = $this->router->generate('blog_activeAccount', array(
            'activeToken' => $user->getActiveToken()
        ), UrlGeneratorInterface::ABSOLUTE_URL);
        $bodyMail = $this->template->render(':email:activeAccount.html.twig', array(
            'generatedUrl' => $generatedUrl
        ));

        $this->userMailer->userMailer($user, 'Activation link', $bodyMail);
    }

    public function activationManager($activeToken){
        $user = $this->doctrine->getRepository(User::class)
            ->findOneBy(array('activeToken' => $activeToken));

        if ($user === null){
            throw new UserException($this->translator->trans('user.not_found', [], 'exception'));
        }
        $user->setIsActive(true);
        $user->setActiveToken(null);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return true;
    }

    public function forgotPasswdEmailManager($email){
        $user = $this->doctrine->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        if ($user === null){
            throw new UserException($this->translator->trans('user.not_found', [], 'exception'));
        }
        $token = $this->generateActiveToken();
        $user->setActiveToken($token);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        $generatedUrl = $this->router->generate('blog_forgot_passwd', array(
            'activeToken' => $token
        ), UrlGeneratorInterface::ABSOLUTE_URL);
        $bodyMail = $this->template->render(':email:forgotPasswd.html.twig', array(
            'generatedUrl' => $generatedUrl
        ));

        $this->userMailer->userMailer($user, 'Forgot password link', $bodyMail);
        return true;
    }

    public function forgotPasswdManager(User $user, $activeToken, $plainPassword){

        $password = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setActiveToken(null);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return true;
    }
}