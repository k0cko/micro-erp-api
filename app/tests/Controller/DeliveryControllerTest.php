<?php

namespace App\Tests;

use App\DataFixtures\ContractorFixtures;
use App\DataFixtures\DeliveryFixtures;
use App\DataFixtures\DeliveryProductFixtures;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\WarehouseFixtures;
use App\Enum\DeliveryProductStatus;
use App\Enum\InquiryStatus;
use App\Enum\ProductMovementType;
use App\Repository\DeliveryProductRepository;
use App\Repository\DeliveryRepository;
use App\Repository\WarehouseProductRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeliveryControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    private KernelBrowser $client;
    private DeliveryRepository $deliveryRepository;
    private DeliveryProductRepository $deliveryProductRepository;
    private WarehouseProductRepository $warehouseProductRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();


        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            UserFixtures::class,
            ContractorFixtures::class,
            WarehouseFixtures::class,
            ProductFixtures::class,
            DeliveryFixtures::class,
            DeliveryProductFixtures::class,
        ]);

        $container = static::getContainer();
        $this->deliveryRepository = $container->get(DeliveryRepository::class);
        $this->deliveryProductRepository = $container->get(DeliveryProductRepository::class);
        $this->warehouseProductRepository = $container->get(WarehouseProductRepository::class);
    }

    private function setTokenForUser(string $username): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => $username,
                'password' => 'password',
            ])
        );

        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['data']['token']));
    }

    public function test_it_completes_delivery_and_creates_warehouse_product_and_movement(): void
    {
        $this->setTokenForUser('admin_user');

        $deliveryId = $this->deliveryRepository->findOneBy(['status' => InquiryStatus::InProgress])->getId();

        $this->client->request(
            method: 'PUT',
            uri: sprintf('/deliveries/%d/complete', $deliveryId),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_OK);

        $delivery = $this->deliveryRepository->find($deliveryId);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(InquiryStatus::Completed->label(), $responseData['data']['status']);


        $receivedDeliveryProduct = $this->deliveryProductRepository->findOneBy([
            'delivery' => $delivery,
            'status' => DeliveryProductStatus::Received
        ]);

        // We know it's only one
        $this->assertSame(ProductMovementType::In, $receivedDeliveryProduct->getProductMovements()->first()->getType());

        $warehouseProduct = $this->warehouseProductRepository->findOneBy([
            'warehouse' => $delivery->getWarehouse(),
            'product' => $receivedDeliveryProduct->getProduct(),
        ]);

        $this->assertSame($receivedDeliveryProduct->getQuantity(), $warehouseProduct->getQuantity());
    }
}
