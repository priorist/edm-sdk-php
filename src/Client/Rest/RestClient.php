<?php
namespace Priorist\AIS\Client\Rest;

use League\OAuth2\Client\Token\AccessToken;
use Priorist\AIS\Client\Collection;


interface RestClient
{
    public function fetchSingle(string $endpoint, $id, array $params) : ?array;
    public function fetchCollection(string $endpoint, array $params) : Collection;
    public function fetch(string $endpoint, array $params = []) : ?array;
    public function create(string $endpoint, array $data = [], array $params = []) : ?array;
    public function queryJson(string $method, string $endpoint, array $params = [], array $body = null) : ?array;
    public function query(string $method, string $endpoint, array $params = [], array $body = null) : ?string;
    public function login(string $userName, string $password) : AccessToken;
    public function getUser();
    public function setAccessToken(AccessToken $accessToken) : RestClient;
    public function getAccessToken() : AccessToken;
}
