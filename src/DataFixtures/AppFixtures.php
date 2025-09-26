<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use App\Entity\Commentaire;
use App\Entity\Image;
use App\Entity\Oignon;
use App\Entity\Pain;
use App\Entity\Sauce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créez des pains
        $pains = [];
        for ($i = 0; $i < 5; $i++) {
            $pain = new Pain();
            $pain->setName($faker->word() . ' Pain');
            $manager->persist($pain);
            $pains[] = $pain;
        }

        // Créez des oignons
        $oignons = [];
        for ($i = 0; $i < 5; $i++) {
            $oignon = new Oignon();
            $oignon->setName($faker->word() . ' Oignon');
            $manager->persist($oignon);
            $oignons[] = $oignon;
        }

        // Créez des sauces
        $sauces = [];
        for ($i = 0; $i < 5; $i++) {
            $sauce = new Sauce();
            $sauce->setName($faker->word() . ' Sauce');
            $manager->persist($sauce);
            $sauces[] = $sauce;
        }

        // Créez des images
        $images = [];
        $numBurgers = 10;
        for ($i = 0; $i < $numBurgers; $i++) {
            $image = new Image();
            $image->setName($faker->imageUrl(640, 480, 'burger'));
            $manager->persist($image);
            $images[] = $image;
        }

        // Créez des burgers et associez les relations
        $burgers = [];
        foreach ($images as $image) {
            $burger = new Burger();
            $burger->setName($faker->word() . ' Burger');
            $burger->setPrice($faker->randomFloat(2, 5, 20));

            // Utilisez le setter correct "setPain"
            $burger->setPain($faker->randomElement($pains)); 
            
            // Utilisez le setter correct "setImage"
            $burger->setImage($image); 

            // Associez des oignons aléatoires
            shuffle($oignons);
            $selectedOignons = array_slice($oignons, 0, rand(1, 3));
            foreach ($selectedOignons as $oignon) {
                $burger->addOignon($oignon);
            }

            // Associez des sauces aléatoires
            shuffle($sauces);
            $selectedSauces = array_slice($sauces, 0, rand(1, 3));
            foreach ($selectedSauces as $sauce) {
                $burger->addSauce($sauce);
            }

            $manager->persist($burger);
            $burgers[] = $burger;
        }

        // Créez des commentaires et associez-les aux burgers
        for ($i = 0; $i < 20; $i++) {
            $commentaire = new Commentaire();
            $commentaire->setName($faker->sentence(6));
            
            // Utilisez le setter correct "setBurger"
            $commentaire->setBurger($faker->randomElement($burgers)); 
            
            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}