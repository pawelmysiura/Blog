<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

class TagControllerTest extends BaseControllerTest
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

    public function testAdminTag()
    {
        $this->client->request('GET', '/admin/tag');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Tags list', $response->getContent());
    }

    public function testDelete()
    {
        $id = 1;
        $this->client->request('GET', '/admin/delete-tag/' . $id);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $tag = $this->em->getRepository(Tag::class)->findOneBy(['id' => $id]);
        $this->assertEquals(null, $tag);
    }
    public function testEdit ()
    {
        $id = 1;
        $newName = 'Test tag';
        $crawler = $this->client->request('GET', '/admin/tag/edit/'.$id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Create')->form();
        $form['create[name]'] = $newName;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $tag = $this->em->getRepository(Tag::class)->findOneBy(['id' => $id])->getName();
        $this->assertEquals($newName, $tag);
    }

    public function testCreate()
    {
        $newName = 'New tag test';
        $crawler = $this->client->request('GET', '/admin/tag/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Create')->form();
        $form['create[name]'] = $newName;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $tag = $this->em->getRepository(Tag::class)->findOneBy(['name' => $newName])->getName();
        $this->assertEquals($newName, $tag);
    }
    public function testSearch()
    {
        $searchName = 'Quid';
        $crawler = $this->client->request('POST', '/admin/search-tag', [
            'search_main' => [
                'search' => $searchName
            ]]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('table > tbody > tr')->count());
    }
}
