<?php

namespace App\DataFixtures;

use App\Factory\ProfielFactory;
use App\Factory\TagsFactory;
use App\Factory\VragenFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProfielFactory::createMany(20);
        TagsFactory::createMany(20);
        VragenFactory::createMany(20);

        $manager->flush();
    }
}
