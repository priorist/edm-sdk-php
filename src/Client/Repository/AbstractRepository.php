<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Rest\RestClient;
use Priorist\AIS\Client\Collection;


abstract class AbstractRepository implements Repository
{
    protected RestClient $client;


    public function __construct(RestClient $client)
    {
        $this->setClient($client);
    }


    public function querySingle(int $id, array $params = [], array $overrideParams = []) : ?array
    {
        return $this->getClient()->querySingle(
            static::getEndpointPath(),
            $id,
            array_merge($params, $overrideParams)
        );
    }


    public function queryCollection(array $params = [], array $overrideParams = []) : Collection
    {
        return $this->getClient()->queryCollection(
            static::getEndpointPath(),
            array_merge(['ordering' => static::getDefaultOrdering()], $params, $overrideParams)
        );
    }


    public function setClient(RestClient $client) : Repository
    {
        $this->client = $client;

        return $this;
    }


    public function getClient() : RestClient
    {
        return $this->client;
    }


    abstract public static function getEndpointPath() : string;
    abstract protected static function getDefaultOrdering() : string;
}
