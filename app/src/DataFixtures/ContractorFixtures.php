<?php

namespace App\DataFixtures;

use App\Entity\Contractor;
use App\Enum\ContractorType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContractorFixtures extends Fixture
{
    public const SUPPLIER_CONTRACTOR_REFERENCE = 'supplier-contractor';
    public const CLIENT_CONTRACTOR_REFERENCE = 'client-contractor';

    public function load(ObjectManager $manager): void
    {
        $supplier = new Contractor(
            'Supplier contractor',
            ContractorType::Supplier,
        );

        $client = new Contractor(
            'Client contractor',
            ContractorType::Client,
        );

        $manager->persist($supplier);
        $manager->persist($client);
        $manager->flush();

        $this->addReference(self::SUPPLIER_CONTRACTOR_REFERENCE, $supplier);
        $this->addReference(self::CLIENT_CONTRACTOR_REFERENCE, $client);
    }
}
