<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Norman Reedus',
        'Andrew Lincoln',
        'Danai Gurira',
        'Melissa McBride',
        'Lauren Cohan',
    ];

    public function load(ObjectManager $manager)
    {
        $program = new Program;
        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->addProgram($this->getReference('program_1', $program));
            $manager->persist($actor);
        }
        $manager->flush();

        $faker  =  Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 45; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_'. rand(1,5)));
            $manager->persist($actor);
            $this->addReference('acteur_' . $i, $actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
