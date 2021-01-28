<?php

namespace App\DataFixtures;

use WW\Faker\Provider\Picture;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Category;

class AppFixtures extends Fixture

{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker-> addProvider(new \Liior\Faker\Prices($faker));
        $faker-> addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
      $faker->addProvider(new \WW\Faker\Provider\Picture($faker));

        for($c=0 ; $c<3; $c++){
$category = new Category;
$category->setName($faker->department)
->setSlug(strtolower($this->slugger->slug($category->getName())));

$manager->persist($category);

for ($p = 0; $p < mt_rand(15 , 20); $p++) {

    $product = new Product;
    $product->setName($faker->productName)
        ->setPrice($faker->price(4000, 20000))
        ->setSlug(strtolower($this->slugger->slug($product->getName())))
        ->setCategory($category)
        ->setShortDescription($faker->paragraph())
        ->setMainPicture($faker->pictureUrl(250,200));

    $manager->persist($product);
}
        }
        $manager->flush();
    }
}