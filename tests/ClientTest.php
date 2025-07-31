<?php

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use League\OAuth2\Client\Token\AccessToken;
use Priorist\EDM\Client\Client;
use Priorist\EDM\Client\Repository\Repository;

class ClientTest extends TestCase
{
    public function testConnection()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertInstanceOf(Repository::class, $client->event);
        $this->assertEquals($client->event, $client->event);
        $this->assertInstanceOf(AccessToken::class, $client->getAccessToken());
        $this->assertIsArray($client->getRestClient()->fetch('categories'));

        return $client;
    }


    #[Depends('testConnection')]
    public function testTokenReuse(Client $originalClient)
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));
        $client->setAccessToken($originalClient->getAccessToken());

        $this->assertIsArray($client->getRestClient()->fetch('categories'));
    }


    #[Depends('testConnection')]
    public function testInvalidRepository(Client $client)
    {
        $this->expectException(InvalidArgumentException::class);
        $client->foo;
    }


    public function testInvalidCredentials()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), 'INVALID_SECRET');

        $this->expectException(InvalidArgumentException::class);
        $client->event->findUpcoming();
    }


    public function testInvalidToken()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), 'INVALID_SECRET');

        $client->setAccessToken(new AccessToken(['access_token' => 'INVALID_TOKEN']));

        $this->expectException(InvalidArgumentException::class);
        $client->event->findUpcoming();
    }
}
