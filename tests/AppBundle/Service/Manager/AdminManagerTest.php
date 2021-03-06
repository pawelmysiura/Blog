<?php

namespace Tests\AppBundle\Service\Manager;


use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\PostRepository;
use AppBundle\Repository\TagRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Manager\AdminManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ManagerRegistry;

class AdminManagerTest extends WebTestCase
{

    public function testCountMainPage()
    {

        $userRepo = $this->createMock(UserRepository::class);
        $userRepo->expects($this->once())
            ->method('ConutUser')
            ->willReturn('test');

        $tagRepo = $this->createMock(TagRepository::class);
        $tagRepo->expects($this->once())
            ->method('ConutTag')
            ->willReturn('test');

        $postRepo = $this->createMock(PostRepository::class);
        $postRepo->expects($this->any())
            ->method('ConutPost')
            ->willReturn('test');

        $categoryRepo = $this->createMock(CategoryRepository::class);
        $categoryRepo->expects($this->once())
            ->method('ConutCategory')
            ->willReturn('test');

        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepo, $tagRepo, $postRepo, $categoryRepo);

        $adminMAnager = new AdminManager($doctrine);

        $return = $adminMAnager->countMainPage();
        $this->assertEquals(
            [
                'title' => 'Main',
                'user' => 'test',
                'postPublished' => 'test',
                'postUnpublished' => 'test',
                'tag' => 'test',
                'category' => 'test'
            ], $return
        );
    }

}