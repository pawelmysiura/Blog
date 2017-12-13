<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseControllerTest
{
    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\CategoryFixtures',
            'AppBundle\DataFixtures\ORM\PostFixtures',
            'AppBundle\DataFixtures\ORM\TagFixtures',
            'AppBundle\DataFixtures\ORM\UserFixtures',
        ];
    }

    public function testAccountSettings()
    {
        $crawler = $this->client->request('GET', '/account/settings');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(2, $crawler->filter('table > tbody > tr')->count());
    }

    public function testChangePassword()
    {
        $crawler = $this->client->request('GET', '/account/change-password');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $form = $crawler->selectButton('Change password')->form();
        $form['change_password[currPassword]'] = '123';
        $form['change_password[plainPassword][first]'] = 'abcabcabc';
        $form['change_password[plainPassword][second]'] = 'abcabcabc';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Password has been changed', $this->client->getResponse()->getContent());
    }
    public function testChangeEmail()
    {
        $this->logIn('joloolo');
        $crawler = $this->client->request('GET', '/account/change-email');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $form = $crawler->selectButton('Change email')->form();
        $form['change_email[password]'] = '123';
        $form['change_email[newEmail]'] = 'test@testtest.test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Email has been changed', $this->client->getResponse()->getContent());
    }

    public function testAdminUser()
    {
        $this->client->request('GET', '/admin/users');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Users list', $response->getContent());
    }

    public function testIsActive()
    {
        $id = 6;
        $this->client->request('GET', '/admin/active/' . $id);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $enable = $this->em->getRepository(User::class)->findOneBy(['id' => $id])->getIsActive();
        $this->assertEquals(1, $enable);
    }

    public function testSearch()
    {
        $searchName = 'wasde';
        $crawler = $this->client->request('POST', '/admin/search-user', [
            'search_main' => [
                'search' => $searchName
            ]]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('table > tbody > tr')->count());
    }
}
