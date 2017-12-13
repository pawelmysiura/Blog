<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $CatList = array(
            'weekend' => 'Weekend',
            'podroze' => 'Podróże',
            'samochody' => 'Nowości Samochodowe',
            'technologie' => 'Technologie',
            'ludzie' => 'Ludzie'
        );

        foreach ($CatList as $key => $name) {
            $Cat = new Category();
            $Cat->setName($name);

            $manager->persist($Cat);
            $this->addReference('category_'.$key, $Cat);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}