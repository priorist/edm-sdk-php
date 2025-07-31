<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Rest\RestClient;
use Priorist\EDM\Client\Collection;

interface Repository
{
    public function fetchSingle($idOrSlug, array $params = []): array | null;
    public function fetchCollection(array $params = []): Collection;
    public function create(array $data = []): array | null;

    public function setClient(RestClient $client): self;
    public function getClient(): RestClient;

    public static function getEndpointPath(): string;
}
