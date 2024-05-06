<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture

{


    public function __construct(protected \Symfony\Component\String\Slugger\SluggerInterface $slugger, protected UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \WW\Faker\Provider\Picture($faker));

        $admin = new User;
        $hash = $this->passwordHasher->hashPassword($admin, 'password');
        $admin
            ->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $users = [];
        for ($u = 0; $u < 5; $u++) {

            $user = new User();
            $user
                ->setEmail("user$u@gmail.com")
                ->setPassword($hash);
            $users[] = $user;
            $manager->persist($user);
        }

        $products = [];

        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->safeColorName())
                ->setOwner($faker->randomElement($users));
            $manager->persist($category);
            for ($p = 0; $p < mt_rand(15, 20); $p++) {

                $product = new Product;
                $product->setName($faker->colorName())
                    ->setPrice($faker->numberBetween(2,3000))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl());
                $products[] = $product;
                $manager->persist($product);
            }
        }
        for ($p = 0; $p < mt_rand(20, 40); $p++) {

            $purchase = new Purchase;
            $purchase->setFullname($faker->name)
                ->setAdress($faker->streetAddress)
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city())
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('- 6 months'));

            $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));

            foreach ($selectedProducts as $product) {
                $purchaseItem = new PurchaseItem;
                $purchaseItem->setProduct($product)
                ->setQuantity(mt_rand(1,4))
                ->setProductName($product->getName())
                ->setProductPrice($product->getPrice())
                ->setTotal(
                    $purchaseItem->getProductPrice() * $purchaseItem->getQuantity()
                )
                ->setPurchase($purchase);
                $manager->persist($purchaseItem);
            }
            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }
            $manager->persist($purchase);
        }
        $manager->flush();
    }
}
