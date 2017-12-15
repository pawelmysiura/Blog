<?php

namespace AppBundle\Service\Manager;


use AppBundle\Entity\User;
use AppBundle\Exception\UserException;
use AppBundle\Service\TokenGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ManagerRegistry as doctrine;
use Symfony\Component\Translation\TranslatorInterface;
use AppBundle\Service\Manager\EmailManager;

class UserManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EmailManager
     */
    private $emailManager;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * UserManager constructor.
     * @param doctrine $doctrine
     * @param UserPasswordEncoderInterface $encoder
     * @param TranslatorInterface $translator
     * @param \AppBundle\Service\Manager\EmailManager $emailManager
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(doctrine $doctrine, UserPasswordEncoderInterface $encoder, TranslatorInterface $translator, EmailManager $emailManager, TokenGenerator $tokenGenerator)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
        $this->translator = $translator;
        $this->emailManager = $emailManager;
        $this->tokenGenerator = $tokenGenerator;
    }


    public function registerManager(User $user)
    {

        if (null !== $user->getId()) {
            throw new UserException($this->translator->trans('user.username_exist', [], 'exception'));
        }
        $token = $this->tokenGenerator->generateActiveToken();
        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setActiveToken($token);
        $user->setIsActive(false);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();
        $this->emailManager->registerMail($user, $user->getActiveToken());

        return true;

    }

    public function activationManager($activeToken){
        if ($activeToken == null){
            throw new UserException($this->translator->trans('user.not_found', [], 'exception'));
        }
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
        $token = $this->tokenGenerator->generateActiveToken();
        $user->setActiveToken($token);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        $this->emailManager->forgotPasswdMail($user, $token);
    }

    public function forgotPasswdManager(User $user, $plainPassword){

        $password = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setActiveToken(null);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return true;
    }
}