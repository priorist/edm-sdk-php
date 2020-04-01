<?php
namespace Priorist\AIS\Client\Rest;

use League\OAuth2\Client\Token\AccessToken;
use Priorist\AIS\Client\Collection;


interface RestClient
{
    public function querySingle(string $endpoint, int $id, array $params) : ?array;
    public function queryCollection(string $endpoint, array $params) : Collection;
    public function query(string $endpoint, array $params = []) : ?array;
    public function queryRaw(string $endpoint, array $params = []) : ?string;
    public function setAccessToken(AccessToken $accessToken) : RestClient;
    public function getAccessToken() : AccessToken;
}
