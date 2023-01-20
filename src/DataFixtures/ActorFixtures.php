<?php

namespace App\DataFixtures;

use App\Entity\Actor;


use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void

    {

        //Puis ici nous demandons à la Factory de nous fournir un Faker

        $faker = Factory::create();


        /**

         * L'objet $faker que tu récupère est l'outil qui va te permettre

         * de te générer toutes les données que tu souhaites

         */


        for($i = 0; $i < 11; $i++) {

            $Actor = new Actor();

            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base

            $Actor->setName($faker->name());
            $text='program_' . $faker->numberBetween(0, 5);

            $Actor->setProgram($this->getReference($text));

            $text2= "season_".$i;
            $this->addReference($text2, $Actor);
            $manager->persist($Actor);

        }


        $manager->flush();

    }


    public function getDependencies(): array

    {

        return [

            ProgramFixtures::class,

        ];

    }

}



