<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//Tout d'abord nous ajoutons la classe Factory de FakerPhp
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre
         * de te générer toutes les données que tu souhaites
         */

        for($i = 0; $i < 10; $i++) {
            $episode = new Episode();
            //Ce Faker va nous permettre d'alimenter l'instance de Episode que l'on souhaite ajouter en base
            $episode->setTitle($faker->text(255));
            $episode->setNumber($faker->numberBetween(1, 10));
            $episode->setSynopsis($faker->paragraphs(10, true));
            $text='season_'. $faker->numberBetween(0, 5);
            $episode->setSeason($this->getReference($text));
            $text2= "episode_".$i;
            $this->addReference($text2, $episode);
            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
           SeasonFixtures::class,
        ];
    }
}
