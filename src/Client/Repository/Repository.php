<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Rest\RestClient;
use Priorist\AIS\Client\Collection;


interface Repository
{
    public function fetchSingle($idOrSlug, array $params = []) : ?array;
    public function fetchCollection(array $params = []) : Collection;
    public function create(array $data = []) : ?array;

    public function setClient(RestClient $client) : Repository;
    public function getClient() : RestClient;

    public static function getEndpointPath() : string;
}
