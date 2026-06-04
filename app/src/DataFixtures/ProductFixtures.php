<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public const PRODUCT_REFERENCE = 'product';

    public function load(ObjectManager $manager): void
    {
        $product = new Product(
            'Product',
            'Description'
        );

        $manager->persist($product);
        $manager->flush();

        $this->setReference(self::PRODUCT_REFERENCE, $product);
    }
}
