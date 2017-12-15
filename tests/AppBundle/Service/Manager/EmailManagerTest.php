<?php

namespace Tests\AppBundle\Service\Manager;


use AppBundle\Entity\User;
use AppBundle\Exception\UserException;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Mailer\UserMailer;
use AppBundle\Service\Manager\AdminManager;
use AppBundle\Service\Manager\UserManager;
use AppBundle\Service\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Service\Manager\EmailManager;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class EmailManagerTest extends WebTestCase
{

    private $router;

    private $template;

    private $userMailer;

    public function setUp(){
        $this->router = $this->createMock(Router::class);
        $this->template = $this->createMock(EngineInterface::class);
        $this->userMailer = $this->createMock(UserMailer::class);
    }

    public function testRegisterMail()
    {
        $user = new User();
        $user->setId(9);
        $user->setUsername('testuser');
        $user->setEmail('passemail@email.com');
        $user->setPassword('test123');
        $user->setActiveToken('1235');
        $user->setIsActive(0);

        $emailManager = new EmailManager($this->router, $this->template, $this->userMailer);

        $token = $emailManager->registerMail($user, $user->getActiveToken());
        $this->assertTrue($token);
    }

    public function testForgotPasswdMail()
    {
        $user = new User();
        $user->setId(9);
        $user->setUsername('testuser');
        $user->setEmail('passemail@email.com');
        $user->setPassword('test123');
        $user->setActiveToken('1235');
        $user->setIsActive(1);

        $emailManager = new EmailManager($this->router, $this->template, $this->userMailer);

        $token = $emailManager->forgotPasswdMail($user, $user->getActiveToken());
        $this->assertTrue($token);

    }
}