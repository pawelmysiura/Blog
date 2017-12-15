<?php

namespace Tests\AppBundle\Service\Manager;


use AppBundle\Entity\User;
use AppBundle\Exception\UserException;
use AppBundle\Service\Manager\UserManager;
use AppBundle\Service\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Service\Manager\EmailManager;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserManagerTest extends WebTestCase
{
    private $translator;

    private $encoder;

    private $doctrine;

    private $emailManager;

    private $tokenGenerator;

    public function setUp(){
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->encoder = $this->createMock(UserPasswordEncoderInterface::class);;
        $this->doctrine = $this->createMock(ManagerRegistry::class);;
        $this->emailManager = $this->createMock(EmailManager::class);;
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
    }

    public function testRegisterUserPass()
    {
        $user = new User();
        $user->setUsername('nicktest');
        $user->setPlainPassword('123456');

        $newToken = '123Tokentest123';

        $password = 'testtesttest';
        $this->encoder->expects($this->once())
            ->method('encodePassword')
            ->willReturn($password);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->doctrine->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);

        $this->tokenGenerator->expects($this->once())
            ->method('generateActiveToken')
            ->willReturn($newToken);

        $this->emailManager->expects($this->once())
            ->method('registerMail')
            ->with($user, $newToken);

        $userManager->registerManager($user);

        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals(null, $user->getId());
        $this->assertEquals($newToken, $user->getActiveToken());
    }

    /**
     * @expectedException Exception
     */
    public function testRegisterUserFail()
    {
        $user = new User();
        $user->setId(8);
        $user->setUsername('nicktest');
        $user->setPlainPassword('123456');

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);
        $userManager->registerManager($user);

        $this->assertEquals(8, $user->getId());
    }

    public function testActivationAccountPass()
    {
        $user = new User();
        $user->setId(9);
        $user->setUsername('testuser');
        $user->setPassword('123sd5f9e8f2');
        $user->setActiveToken('test123');
        $user->setIsActive(0);

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);
        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->once())
            ->method('findOneBy')
            ->willReturn($user);

        $this->doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->doctrine->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);

        $userManager->activationManager($user->getActiveToken());

        $this->assertEquals(1, $user->getIsActive());
        $this->assertEquals(null, $user->getActiveToken());
    }

    /**
     * @expectedException Exception
     */
    public function testActivationAccountFailToken()
    {
        $user = new User();
        $user->setId(9);
        $user->setUsername('testuser');
        $user->setPassword('test123');
        $user->setActiveToken('1235');
        $user->setIsActive(0);

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);
        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->doctrine->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);

        $userManager->activationManager($user->getActiveToken());
        $this->assertEquals(0, $user->getIsActive());
    }

    /**
     * @expectedException Exception
     */
    public function testActivationAccountFailNullToken()
    {
        $user = new User();
        $user->setId(9);
        $user->setUsername('testuser');
        $user->setPassword('test123');
        $user->setActiveToken(null);
        $user->setIsActive(0);
        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);
        $userManager->activationManager($user->getActiveToken());
        $this->assertEquals(0, $user->getIsActive());
    }

    public function testforgotPasswdEmailManagerPass()
    {

        $user = new User();
        $user->setId(10);
        $user->setUsername('testuser');
        $user->setEmail('passemail@email.com');
        $user->setPassword('test123');
        $user->setActiveToken(null);
        $user->setIsActive(1);

        $newToken = '123Token123';

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->once())
            ->method('findOneBy')
            ->willReturn($user);

        $this->doctrine->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);

        $this->tokenGenerator->expects($this->once())
            ->method('generateActiveToken')
            ->willReturn($newToken);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->doctrine->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);

        $userManager->forgotPasswdEmailManager($user->getEmail());

        $this->assertEquals($newToken, $user->getActiveToken());
    }

    /**
     * @expectedException Exception
     */
    public function testforgotPasswdEmailManagerFail()
    {
        $user = new User();
        $user->setId(10);
        $user->setUsername('testuser');
        $user->setEmail('fakeemail@fake.com');

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->doctrine->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);
        $userManager->forgotPasswdEmailManager($user->getEmail());
    }

    public function testforgotPasswdManagerPass()
    {
        $user = new User();
        $user->setId(10);
        $user->setUsername('testuser');
        $user->setEmail('passemail@email.com');
        $user->setPassword('oldPassword');
        $user->setPlainPassword('newPlainPasssword');
        $user->setActiveToken('changingPasswordToken');
        $user->setIsActive(1);

        $newEncodePassword = '123new123password';

        $userManager = new UserManager($this->doctrine, $this->encoder, $this->translator, $this->emailManager, $this->tokenGenerator);

        $this->encoder->expects($this->once())
            ->method('encodePassword')
            ->willReturn($newEncodePassword);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->doctrine->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);

        $userManager->forgotPasswdManager($user, $user->getPlainPassword());
        $this->assertEquals($newEncodePassword, $user->getPassword());
        $this->assertNull($user->getActiveToken());
    }
}