<?php

namespace App\DataFixtures;

use App\Entity\Contractor;
use App\Entity\Delivery;
use App\Entity\User;
use App\Entity\Warehouse;
use App\Enum\InquiryStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeliveryFixtures extends Fixture
{
    public const DELIVERY_REFERENCE = 'delivery';

    public function load(ObjectManager $manager): void
    {
        $delivery = new Delivery(
            new \DateTimeImmutable(),
            InquiryStatus::InProgress,
            $this->getReference(ContractorFixtures::SUPPLIER_CONTRACTOR_REFERENCE, Contractor::class),
            $this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class),
            $this->getReference(WarehouseFixtures::WAREHOUSE_REFERENCE, Warehouse::class),
        );

        $manager->persist($delivery);
        $manager->flush();

        $this->addReference(self::DELIVERY_REFERENCE, $delivery);
    }
}
