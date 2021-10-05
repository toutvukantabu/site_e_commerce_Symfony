<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\FileUploader;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Flex\Path;

class AppFixtures extends Fixture

{
    protected $slugger;
    protected $encoder;
   

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    
    }
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \WW\Faker\Provider\Picture($faker));

        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setFullName($faker->name())
            ->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $users = [];
        for ($u = 0; $u < 5; $u++) {

            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setFullName($faker->name())
                ->setEmail("user$u@gmail.com")
                ->setPassword($hash);
            $users[] = $user;
            $manager->persist($user);
        }

        $products = [];
        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->department)
                ->setOwner($faker->randomElement($users));
            $manager->persist($category);
            for ($p = 0; $p < mt_rand(15, 20); $p++) {

                $product = new Product;
                $product->setName($faker->productName)
                    ->setPrice($faker->price(4000, 20000))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture('/product/test.png');
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
