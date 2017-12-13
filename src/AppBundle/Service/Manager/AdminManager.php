<?php

namespace AppBundle\Service\Manager;


use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry as doctrine;

class AdminManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    public function __construct(doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function countMainPage()
    {
        $userRepo = $this->doctrine->getRepository(User::class);
        $userCount = $userRepo->ConutUser();
        $tagRepo = $this->doctrine->getRepository(Tag::class);
        $tagCount = $tagRepo->ConutTag();
        $postRepo = $this->doctrine->getRepository(Post::class);
        $postPublishedCount = $postRepo->ConutPost(array(
            'status' => 'published'
        ));
        $postUnpublishedCount = $postRepo->ConutPost(array(
            'status' => 'unpublished'
        ));
        $categoryRepo = $this->doctrine->getRepository(Category::class);
        $categoryCount = $categoryRepo->ConutCategory();

        return array(
            'title' => 'Main',
            'user' => $userCount,
            'postPublished' => $postPublishedCount,
            'postUnpublished' => $postUnpublishedCount,
            'tag' => $tagCount,
            'category' => $categoryCount
        );

    }
}