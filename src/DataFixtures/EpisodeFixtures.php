<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $program = new Program;
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 90; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->title);
            $episode->setNumber($faker->numberBetween(1,15));
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_'.rand(0, 5)));
            $episode->setProgram($this->getReference('program_'. rand(0,5)));
            $manager->persist($episode);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [ SeasonFixtures::class];
    }
}