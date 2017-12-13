<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends BaseControllerTest
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

    public function testAdminCategory()
    {
        $this->client->request('GET', '/admin/categories');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Categories list', $response->getContent());
    }

    public function testDelete()
    {
        $id = 1;
        $this->client->request('GET', '/admin/delete-category/' . $id);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $category = $this->em->getRepository(Category::class)->findOneBy(['id' => $id]);
        $this->assertEquals(null, $category);
    }
    public function testEdit ()
    {
        $id = 1;
        $newName = 'Test category';
        $crawler = $this->client->request('GET', '/admin/category/edit/'.$id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Create')->form();
        $form['create[name]'] = $newName;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $category = $this->em->getRepository(Category::class)->findOneBy(['id' => $id])->getName();
        $this->assertEquals($newName, $category);
    }

    public function testCreate()
    {
        $newName = 'New category test';
        $crawler = $this->client->request('GET', '/admin/category/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Create')->form();
        $form['create[name]'] = $newName;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $category = $this->em->getRepository(Category::class)->findOneBy(['name' => $newName])->getName();
        $this->assertEquals($newName, $category);
    }
    public function testSearch()
    {
        $searchName = 'Technologie';
        $crawler = $this->client->request('POST', '/admin/search-category', [
            'search_main' => [
                'search' => $searchName
            ]]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('table > tbody > tr')->count());
    }
}
