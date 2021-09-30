<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $min = 1;
        $max = 100;

        $currencyEur = new Currency();
        $currencyEur->setId("EUR");
        $currencyEur->setTitle("Euro");

        $manager->persist($currencyEur);

        for ($i = 0; $i <= 20; $i++) {
            $product = new Product();
            $product->setTitle('Product' . $i);
            $product->setDescription('Description' . $i);
            $product->setPrice(mt_rand($min * 100, $max * 100) / 100);
            $product->setStatus(true);
            $product->setCurrency($currencyEur);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
