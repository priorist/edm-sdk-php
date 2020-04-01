<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Rest\RestClient;
use Priorist\AIS\Client\Collection;


interface Repository
{
    public function querySingle(int $id, array $params = []) : ?array;
    public function queryCollection(array $params = []) : Collection;

    public function setClient(RestClient $client) : Repository;
    public function getClient() : RestClient;

    public static function getEndpointPath() : string;
}
