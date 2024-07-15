<?php

namespace App\DataFixtures;

//! pour envoyer les fixtures dans la base de données, on doit utiliser la commande suivante:
//! php bin/console doctrine:fixtures:load

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Categorie;
use App\Entity\Testimonial;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface  $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    // check php faker library to generate fake data for your fixtures
    public function load(ObjectManager $manager): void {
        $faker = Factory::create();
        $faker->addProvider(new \Xvladqt\Faker\LoremFlickrProvider($faker));


        //TODO l'entité: categorie



        $nomsCategories = ['Batterie', 'Electronique', 'Caisse Claire', 'Cymbales', 'Hardware', 'Baguettes', 'Peaux', 'Housse'];
        $delivery_area = ['worldwide', 'europe only', 'france only'];
        $categories = [];

        //* Création des catégories
        foreach ($nomsCategories as $i => $cat) {
            $categorie = new Category();
            $categorie->setName($cat);
            $manager->persist($categorie);
            $categories[] = $categorie;
            $this->addReference('category_' . ($i+1), $categorie);
        }
        //* Création des users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    'toto123' // Remplacez ceci par le mot de passe que vous voulez utiliser pour les utilisateurs de test
                ))
                ->setRoles(['ROLE_USER'])
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhoneNumber($faker->phoneNumber)
                ->setShippingAddress($faker->address)
                ->setBillingAdress($faker->address)
                ->setActive(1)
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-3 months')->format('Y-m-d H:i:s')));
            $manager->persist($user);
            $this->addReference('user_' . ($i+1), $user);
        }
        //* Création des témoignages
        for ($i = 0; $i < 10; $i++) {
            $testimonial = new Testimonial();
            $testimonial->setContent($faker->sentence(rand(10, 15)))
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-3 months')->format('Y-m-d H:i:s')));

            $testimonial->setUser($this->getReference('user_' . rand(1, 10)));
            $manager->persist($testimonial);
            $this->addReference('testimonial_' . ($i+1), $testimonial);
        }
        //* Création des produits
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->setName($faker->word)
                ->setDescription($faker->sentence)
                ->setPrice($faker->randomFloat(2, 10, 100))
                ->setSku($faker->ean13)
                ->setStockQuantity($faker->numberBetween(0, 100))
                ->setActive(1)
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-3 months')->format('Y-m-d H:i:s')))
                ->setRate($faker->randomFloat(2, 0, 5))
                ->setLightOn($faker->boolean)
                ->setDeliveryArea($delivery_area[rand(0, 2)])
                ->setDeliveryDelay($faker->numberBetween(1, 10));

            $product->setCategory($this->getReference('category_' . rand(1, 8)));
            $manager->persist($product);
        }



            $manager->flush();
    }
}