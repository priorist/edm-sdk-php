<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Rest;

use League\OAuth2\Client\Token\AccessToken;
use Priorist\EDM\Client\Collection;

interface RestClient
{
    public function fetchSingle(string $endpoint, $id, array $params): array | null;
    public function fetchCollection(string $endpoint, array $params): Collection;
    public function fetch(string $endpoint, array $params = []): array | null;
    public function create(string $endpoint, array $data = [], array $params = []): array | null;
    public function queryJson(string $method, string $endpoint, array $params = [], array | null $body = null): array | null;
    public function query(string $method, string $endpoint, array $params = [], array | null $body = null): string | null;
    public function login(string $userName, string $password): AccessToken;
    public function getUser();
    public function setAccessToken(AccessToken $accessToken): self;
    public function getAccessToken(): AccessToken;
}
