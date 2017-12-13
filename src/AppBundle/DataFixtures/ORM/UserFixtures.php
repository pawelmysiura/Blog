<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-10
 * Time: 12:18
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     *
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userList = array(
            self::getUser('Admin', 'tag@czikiczaka.pl', '123', 'ROLE_SUPER_ADMIN', 1, NULL),
            self::getUser('Pawel', 'pawmastero@gmail.com', '123', 'ROLE_ADMIN', 1, NULL),
            self::getUser('Tomek', 'tomek@czikiczaka.pl', '123', 'ROLE_EDITOR', 1, NULL),
            self::getUser('wasde', 'wasde@czikiczaka.pl', '123', 'ROLE_EDITOR', 1, NULL),
            self::getUser('joloolo', 'joloolo@czikiczaka.pl', '123', 'ROLE_USER', 1, NULL),
            self::getUser('test', 'test@czikiczaka.pl', '123', 'ROLE_USER', 0, 'Aeyo1vdf533bvfddsfsd')

        );

        $encoder = $this->container->get('security.password_encoder');
        foreach ($userList as $userDetails) {
            $user = new User();
            $password = $encoder->encodePassword($user, $userDetails['password']);
            $user->setUsername($userDetails['username']);
            $user->setEmail($userDetails['email']);
            $user->setPassword($password);
            $user->setRoles(array($userDetails['role']));
            $user->setIsActive($userDetails['isActive']);
            $user->setActiveToken($userDetails['activeToken']);


            $this->addReference('user_' . $userDetails['username'], $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public static function getUser(string $username, string $email, string $password, string $role, int $isActive, $activeToken): array
    {
        return [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'isActive' => $isActive,
            'activeToken' => $activeToken
        ];
    }


    public function getOrder()
    {
        return 0;
    }

}