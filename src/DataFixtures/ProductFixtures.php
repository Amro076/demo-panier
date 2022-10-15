<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $faker = \Faker\Factory::create('fr_FR');

         for ($i =0; $i < mt_rand(8, 10); $i++){
            $product = new Product;

            $product->setTitle($faker->sentence(3))
                    ->setImage($faker->imageUrl)
                    ->setPrice($faker->randomFloat(2,10,100));

                    $manager->persist($product);

         }
    

        $manager->flush();
    }
}
