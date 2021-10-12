<?php
namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Rest\RestClient;
use Priorist\EDM\Client\Collection;


interface Repository
{
    public function fetchSingle($idOrSlug, array $params = []) : ?array;
    public function fetchCollection(array $params = []) : Collection;
    public function create(array $data = []) : ?array;

    public function setClient(RestClient $client) : Repository;
    public function getClient() : RestClient;

    public static function getEndpointPath() : string;
}
