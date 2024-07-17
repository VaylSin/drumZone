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

         $products = [];


        $nomsCategories = ['Batterie', 'Electronique', 'Caisse Claire', 'Cymbales', 'Hardware', 'Baguettes', 'Peaux', 'Housse'];
        $delivery_area = ['Monde Entier', 'Zone Europe', 'France Uniquement'];
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
        for ($i = 0; $i < 20; $i++) {
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
            $this->addReference('user_' . $i, $user);
        }
        
        //* Création des produits
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->setName($faker->word)
                ->setDescription($faker->words(rand(25,50), true))
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
                // Création des images pour chaque produit
                $numImages = rand(1, 4); // Génère un nombre aléatoire d'images entre 1 et 4
                for ($j = 0; $j < $numImages; $j++) {
                    $image = new Image();
                    $image->setUrl($faker->imageUrl(640, 480, 'nature', true))
                        ->setAlt('Description de l\'image ' . $j)
                        ->setProduct($product); // Associe l'image au produit
                    $manager->persist($image);
                }
                if (mt_rand(1, 5) === 1) { // 20% de chance d'avoir un solde
                    $discount = mt_rand(10, 50); // Un solde aléatoire entre 10% et 50%
                    $product->setDiscount($discount);
                } else {
                    $product->setDiscount(0); // Pas de solde
                }

            $product->setCategory($this->getReference('category_' . rand(1, 8)));
            $manager->persist($product);
            $products[] = $product; // Ajout du produit au tableau $products

        }
        //* Création des témoignages assignés à un produit et à un utilisateur
        for ($i = 0; $i < 100; $i++) {
            $testimonial = new Testimonial();
            $testimonial->setContent($faker->sentence(rand(10, 15)))
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-3 months')->format('Y-m-d H:i:s')))
                ->setUser($this->getReference('user_' . rand(0, 19))) // Assignation aléatoire d'un utilisateur
                ->setProduct($products[array_rand($products)]); // Assignation aléatoire d'un produit
            $testimonial->setUser($this->getReference('user_' . rand(0, 19)));
            $manager->persist($testimonial);
        }



            $manager->flush();
    }
}
