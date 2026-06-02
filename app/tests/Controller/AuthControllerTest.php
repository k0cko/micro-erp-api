<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AuthControllerTest extends WebTestCase
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

    public function test_it_returns_token_when_credentials_are_valid(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'test',
                'password' => 'password',
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_OK);

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data['data']);
    }

    public function test_it_returns_401_when_password_is_wrong(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'test',
                'password' => 'wrong_password',
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_401_when_user_does_not_exist(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'wrong_user',
                'password' => 'wrong_password',
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_401_when_user_is_soft_deleted(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'deleted_user',
                'password' => 'password',
            ])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_UNAUTHORIZED);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
