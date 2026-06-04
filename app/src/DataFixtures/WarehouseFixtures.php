<?php

namespace App\DataFixtures;

use App\Entity\Warehouse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WarehouseFixtures extends Fixture
{
    public const WAREHOUSE_REFERENCE = 'warehouse';

    public function load(ObjectManager $manager): void
    {
        $warehouse = new Warehouse(
            'Warehouse'
        );

        $manager->persist($warehouse);
        $manager->flush();

        $this->setReference(self::WAREHOUSE_REFERENCE, $warehouse);
    }
}
