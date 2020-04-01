<?php

use PHPUnit\Framework\TestCase;
use League\OAuth2\Client\Token\AccessToken;

use Priorist\AIS\Client\Client;
use Priorist\AIS\Client\Repository\Repository;


class ClientTest extends TestCase
{
    public function testConnection()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertInstanceOf(Repository::class, $client->event);
        $this->assertEquals($client->event, $client->event);
        $this->assertInstanceOf(AccessToken::class, $client->getAccessToken());
        $this->assertIsArray($client->getRestClient()->query('categories'));

        return $client;
    }


    /**
     * @depends testConnection
     */
    public function testTokenReuse(Client $originalClient)
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));
        $client->setAccessToken($originalClient->getAccessToken());

        $this->assertIsArray($client->getRestClient()->query('categories'));
    }


    /**
     * @depends testConnection
     */
    public function testInvalidRepository(Client $client)
    {
        $this->expectException(InvalidArgumentException::class);
        $client->foo;
    }


    public function testInvalidCredentials()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), 'INVALID_SECRET');

        $this->expectException(InvalidArgumentException::class);
        $client->event->findUpcoming();
    }


    public function testInvalidToken()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), 'INVALID_SECRET');

        $client->setAccessToken(new AccessToken(['access_token' => 'INVALID_TOKEN']));

        $this->expectException(InvalidArgumentException::class);
        $client->event->findUpcoming();
    }


    public function testInvalidJson()
    {
        $client = new Client('https://httpstat.us', getenv('CLIENT_ID'), 'INVALID_SECRET');

        $client->setAccessToken(new AccessToken(['access_token' => 'DOESNT_MATTER']));

        $this->expectException(UnexpectedValueException::class);
        $client->getRestClient()->query('/200');
    }


    public function testInvalidResponseCode()
    {
        $client = new Client('https://httpstat.us', getenv('CLIENT_ID'), 'INVALID_SECRET');

        $client->setAccessToken(new AccessToken(['access_token' => 'DOESNT_MATTER']));

        $this->expectException(RuntimeException::class);
        $client->getRestClient()->query('/500');
    }
}
