<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use App\Enum\UserRole;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();


        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([UserFixtures::class]);
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

    public function test_it_returns_409_when_duplicate_username_exists(): void
    {
        $this->setTokenForUser('admin_user');

        $this->client->request(
            method: 'POST',
            uri: '/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'worker_user',
                'password' => 'password',
                'confirmed_password' => 'password',
                'first_name' => 'worker_user',
                'last_name' => 'worker_user',
                'role' => UserRole::Worker,
            ])
        );
        
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_CONFLICT);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertSame('User with username "worker_user" already exists.', $responseData['error']);
    }

    public function test_it_returns_403_when_insufficient_create_permissions(): void
    {
        $this->setTokenForUser('admin_user');

        $this->client->request(
            method: 'POST',
            uri: '/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'new_super_admin_user',
                'password' => 'password',
                'confirmed_password' => 'password',
                'first_name' => 'new_super_admin_user',
                'last_name' => 'new_super_admin_user',
                'role' => UserRole::SuperAdmin,
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_FORBIDDEN);

        $this->setTokenForUser('worker_user');

        $this->client->request(
            method: 'POST',
            uri: '/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'new_worker_user',
                'password' => 'password',
                'confirmed_password' => 'password',
                'first_name' => 'new_worker_user',
                'last_name' => 'new_worker_user',
                'role' => UserRole::Worker,
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_it_returns_403_when_insufficient_delete_permissions(): void
    {
        $this->setTokenForUser('admin_user');

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);

        $this->client->request(
            method: 'DELETE',
            uri: sprintf('/users/%d', $userRepository->findOneBy([
            'username' => 'super_admin_user'
        ])->getId()),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_FORBIDDEN);

        $this->setTokenForUser('worker_user');

        $this->client->request(
            method: 'DELETE',
            uri: sprintf('/users/%d', $userRepository->findOneBy([
            'username' => 'worker_user'
        ])->getId()),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_it_returns_401_when_unauthenticated(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'new_worker_user',
                'password' => 'password',
                'confirmed_password' => 'password',
                'first_name' => 'new_worker_user',
                'last_name' => 'new_worker_user',
                'role' => UserRole::Worker,
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_UNAUTHORIZED);

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);

        $this->client->request(
            method: 'DELETE',
            uri: sprintf('/users/%d', $userRepository->findOneBy(['username' => 'worker_user'])->getId()),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_201_when_creating_users(): void
    {
        $this->setTokenForUser('admin_user');

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);

        $this->client->request(
            method: 'POST',
            uri: '/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'new_worker_user',
                'password' => 'password',
                'confirmed_password' => 'password',
                'first_name' => 'new_worker_user',
                'last_name' => 'new_worker_user',
                'role' => UserRole::Worker,
            ])
        );
        
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_CREATED);
        $this->assertNotNull($userRepository->findOneBy(['username' => 'new_worker_user']));

        $workerResponseData = json_decode($this->client->getResponse()->getContent(), true)['data'];

        $this->assertSame('new_worker_user', $workerResponseData['username']);
        $this->assertSame(UserRole::Worker->label(), $workerResponseData['role']);
        $this->assertArrayNotHasKey('password', $workerResponseData);
    
        $this->client->request(
            method: 'POST',
            uri: '/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'new_admin_user',
                'password' => 'password',
                'confirmed_password' => 'password',
                'first_name' => 'new_admin_user',
                'last_name' => 'new_admin_user',
                'role' => UserRole::Admin,
            ])
        );
        
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_CREATED);
        $this->assertNotNull($userRepository->findOneBy(['username' => 'new_admin_user']));

        $adminResponseData = json_decode($this->client->getResponse()->getContent(), true)['data'];

        $this->assertSame('new_admin_user', $adminResponseData['username']);
        $this->assertSame(UserRole::Admin->label(), $adminResponseData['role']);
        $this->assertArrayNotHasKey('password', $adminResponseData);
    }

    public function test_it_returns_204_when_deleting_users(): void
    {
        $this->setTokenForUser('super_admin_user');

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);

        $adminId = $userRepository->findOneBy(['username' => 'admin_user'])->getId();
        $workerId = $userRepository->findOneBy(['username' => 'worker_user'])->getId();

        $this->client->request(
            method: 'DELETE',
            uri: sprintf('/users/%d', $adminId),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_NO_CONTENT);
        $this->assertEmpty($this->client->getResponse()->getContent());

        // The kernel must be rebooted after each delete request to get a fresh entity manager.
        // Without this, Doctrine serves stale entities despite the DB being updated.
        self::ensureKernelShutdown();
        self::bootKernel();

        $adminUser = static::getContainer()
            ->get(UserRepository::class)
            ->withDeleted(fn() => static::getContainer()
                ->get(UserRepository::class)
                ->find($adminId)
            );
        $this->assertNotNull($adminUser->getDeletedAt());

        $this->setTokenForUser('super_admin_user');

        $this->client->request(
            method: 'DELETE',
            uri: sprintf('/users/%d', $workerId),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_NO_CONTENT);
        $this->assertEmpty($this->client->getResponse()->getContent());

        self::ensureKernelShutdown();
        self::bootKernel();

        $workerUser = static::getContainer()
            ->get(UserRepository::class)
            ->withDeleted(fn() => static::getContainer()
                ->get(UserRepository::class)
                ->find($workerId)
            );
        $this->assertNotNull($workerUser->getDeletedAt());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
