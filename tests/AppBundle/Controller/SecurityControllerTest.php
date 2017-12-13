<?php

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends BaseControllerTest
{
    public function setUp()
    {
        $this->setEm();
        $this->fixtureSetUp();
        $this->client = static::createClient();
    }

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\CategoryFixtures',
            'AppBundle\DataFixtures\ORM\PostFixtures',
            'AppBundle\DataFixtures\ORM\TagFixtures',
            'AppBundle\DataFixtures\ORM\UserFixtures',
        ];
    }

    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Log in')->form();

        $form['login[username]'] = 'Tomek';
        $form['login[password]'] = '123';

        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Latest posts', $this->client->getResponse()->getContent());
    }

    public function testRegister()
    {

        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/register');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Register')->form();

        $form['register[username]'] = 'user1';
        $form['register[plainPassword][first]'] = '123123123';
        $form['register[plainPassword][second]'] = '123123123';
        $form['register[email]'] = 'acrpolska@gmail.com';
        $form['register[checkbox]'] = 1;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('success.security.register', [], 'message'), $this->client->getResponse()->getContent());

    }
    public function testActiveAccount(){
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $userRepo = $em->getRepository(User::class)->findOneBy(array(
            'username' => 'test'
        ));
        $activeToken = $userRepo->getActiveToken();
        $this->client->request('GET', '/activeAccount/'.$activeToken);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('success.security.activation', [],'message'), $this->client->getResponse()->getContent());

    }

    public function testForgotPasswdEmail()
    {
        $container = self::$kernel->getContainer();

        $crawler = $this->client->request('GET', '/forgot_password');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Send')->form();

        $form['forgot_passwd_email[email]'] = 'test@czikiczaka.pl';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('success.security.forgotPassword', [], 'message'), $this->client->getResponse()->getContent());

    }
    public function testForgotPasswd(){

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array(
            'username' => 'test'
        ));
        $activeToken = $user->getActiveToken();
        $crawler = $this->client->request('GET', '/forgot_password/'.$activeToken);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();
        $form['forgot_passwd[plainPassword][first]'] = 'abcabcabc';
        $form['forgot_passwd[plainPassword][second]'] = 'abcabcabc';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('success.security.activePassword', [],'message'), $this->client->getResponse()->getContent());
    }
}