<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\Table(name="blog_category")
 */
class Category extends AbstractTaxonomy
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="Post",
     *     mappedBy="category"
     * )
     */
    protected $posts;
}