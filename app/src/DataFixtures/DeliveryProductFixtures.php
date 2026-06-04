<?php

namespace App\DataFixtures;

use App\Entity\Delivery;
use App\Entity\DeliveryProduct;
use App\Entity\Product;
use App\Enum\DeliveryProductStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeliveryProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $deliveryProduct = new DeliveryProduct(
            $this->getReference(DeliveryFixtures::DELIVERY_REFERENCE, Delivery::class),
            DeliveryProductStatus::Pending,
            $this->getReference(ProductFixtures::PRODUCT_REFERENCE, Product::class),
            1
        );

        $manager->persist($deliveryProduct);
        $manager->flush();
    }
}
