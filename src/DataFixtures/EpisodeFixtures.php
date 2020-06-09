<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 1; $i <= 50; $i++){
            $episode = new Episode();
            $episode ->setSeason($this->getReference('season_'.rand(1, 50)));
            $episode ->setTitle($faker->title);
            $episode ->setNumber($faker->randomDigitNotNull);
            $episode ->setSynopsis($faker->text);
            $manager ->persist($episode);
            $this->addReference('episode_' . $i, $episode);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}