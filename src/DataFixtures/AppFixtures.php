<?php

namespace App\DataFixtures;

use App\Factory\ProfielFactory;
use App\Factory\TagsFactory;
use App\Factory\VragenFactory;
use App\Factory\AntwoordenFactory;
use App\Entity\Tags;
use App\Entity\Profiel;
use App\Entity\Vragen;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tagNames = [
            'PHP', 'JavaScript', 'Python', 'Java', 'C#', 'C++', 'Rust',
            'HTML', 'CSS', 'React', 'Vue', 'Angular', 'Node.js',
            'Laravel', 'Symfony', 'Django', 'Spring Boot',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            'Bootstrap', 'Tailwind', 'SCSS',
            'TypeScript', 'Git', 'Docker', 'AWS', 'Azure'
        ];
        
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tag = new Tags();
            $tag->setNaam($tagName);
            $manager->persist($tag);
            $tags[] = $tag;
        }
        $manager->flush();

        $profielen = ProfielFactory::createMany(10);
        foreach ($profielen as $profiel) {
            $shuffledTags = $tags;
            shuffle($shuffledTags);
            $tagCount = rand(2, 5);
            for ($i = 0; $i < $tagCount && $i < count($shuffledTags); $i++) {
                $profiel->addTag($shuffledTags[$i]);
            }
        }
        $manager->flush();

        $vragen = VragenFactory::createMany(20);
        foreach ($vragen as $vraag) {
            $shuffledTags = $tags;
            shuffle($shuffledTags);
            $tagCount = rand(2, 4);
            for ($i = 0; $i < $tagCount && $i < count($shuffledTags); $i++) {
                $vraag->addTag($shuffledTags[$i]);
            }
        }
        $manager->flush();

        AntwoordenFactory::createMany(30);

        $manager->flush();
    }
}

