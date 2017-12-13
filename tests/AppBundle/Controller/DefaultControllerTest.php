<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends BaseControllerTest
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

    /**
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPages($url){

        $this->client->request('GET', $url);
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }
    public function testContact(){

        $crawler = $this->client->request('GET', '/contact');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Contact', $response->getContent());
        $form = $crawler->selectButton('Send')->form();

        $form['contact[email]'] = 'test@test.pl';
        $form['contact[subject]'] = 'test';
        $form['contact[message]'] = 'test test test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('The message has been sent', $response->getContent());
    }

    public function testAdmin(){
        $this->client->request('GET', '/admin/');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Statistic', $response->getContent());
    }
    public function urlProvider(){
        yield ['/'];
        yield ['/about'];
    }
}
