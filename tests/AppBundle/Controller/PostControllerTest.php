<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends BaseControllerTest
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

    public function testNews()
    {
        $crawler = $this->client->request('GET', '/news');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(5, $crawler->filter('article')->count());
    }

    public function testCategory()
    {
        $slug = 'ludzie';
        $crawler = $this->client->request('GET', '/category/'.$slug);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(2, $crawler->filter('article')->count());
    }

    public function testTag()
    {
        $slug = 'accius';
        $crawler = $this->client->request('GET', '/tag/'.$slug);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('article')->count());
    }
    public function testPost()
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => 4]);
        $slug = $post->getSlug();
        $title = $post->getTitle();
        $this->client->request('GET', '/'.$slug);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($title, $this->client->getResponse()->getContent());
    }

    public function testArchive()
    {
        $slug = '2017/11';
        $crawler = $this->client->request('GET', '/archive/'.$slug);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('article')->count());
    }


    public function testAdminPost()
    {
        $this->client->request('GET', '/admin/posts');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Posts list', $response->getContent());
    }

    public function testDelete()
    {
        $id = 1;
        $this->client->request('GET', '/admin/delete-post/' . $id);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id]);
        $this->assertEquals(null, $post);
    }
    public function testEdit ()
    {
        $id = 1;
        $cravler = $this->client->request('GET', '/admin/post/edit/'.$id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $cravler->selectButton('Create post')->form();
        $form['post[title]'] = 'Test';
        $form['post[category]']->setValue(1);
        $form['post[tags]']->setValue([3, 8]);
        $form['post[content]'] = 'Test';
        $form['post[publishedDate][date][month]']->setValue(12);
        $form['post[publishedDate][date][day]']->setValue(10);
        $form['post[publishedDate][date][year]']->setValue(2017);
        $form['post[publishedDate][time][hour]']->setValue(11);
        $form['post[publishedDate][time][minute]']->setValue(29);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $title = $this->em->getRepository(Post::class)->findOneBy(['id' => $id])->getTitle();
        $this->assertEquals('Test', $title);
    }

    public function testCreate()
    {
        $newPost = 'New post';
        $cravler = $this->client->request('GET', '/admin/post/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $cravler->selectButton('Create post')->form();
        $form['post[title]'] = $newPost;
        $form['post[category]']->setValue(1);
        $form['post[tags]']->setValue([3, 8]);
        $form['post[content]'] = 'Test';
        $form['post[publishedDate][date][month]']->setValue(12);
        $form['post[publishedDate][date][day]']->setValue(10);
        $form['post[publishedDate][date][year]']->setValue(2017);
        $form['post[publishedDate][time][hour]']->setValue(11);
        $form['post[publishedDate][time][minute]']->setValue(29);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $title = $this->em->getRepository(Post::class)->findOneBy(['title' => $newPost])->getTitle();
        $this->assertEquals($newPost, $title);
    }
    public function testSearch()
    {
        $searchName = 'Nullam aliquet dignissim condimentum. Quisque porta tempor.';
        $crawler = $this->client->request('POST', '/admin/search', [
            'search_main' => [
                'search' => $searchName
            ]]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('table > tbody > tr')->count());
    }
}
