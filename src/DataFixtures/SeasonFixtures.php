<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 1; $i <= 50; $i++){
            $season = new Season();
            $season ->setProgram($this->getReference("program_" .rand(0, 5)));
            $season ->setNumber($faker->randomDigitNotNull);
            $season ->setYear($faker->year);
            $season ->setDescription($faker->text);
            $manager ->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }
        public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}