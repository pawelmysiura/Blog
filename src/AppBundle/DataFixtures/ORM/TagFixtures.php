<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;

class TagFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $taglist = array(
            'Lorem',
            'Fugiunt',
            'Aristophanem',
            'Quid',
            'Praeteritis',
            'Nihil',
            'Qua',
            'Quod',
            'Reges',
            'Scrupulum',
            'Maximus',
            'Accius'
        );

        foreach ($taglist as $key => $name) {
            $Tag = new Tag();
            $Tag->setName($name);

            $manager->persist($Tag);
            $this->addReference('tag_'.$name, $Tag);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}