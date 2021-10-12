<?php
namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Rest\RestClient;
use Priorist\EDM\Client\Collection;


abstract class AbstractRepository implements Repository
{
    protected RestClient $client;


    public function __construct(RestClient $client)
    {
        $this->setClient($client);
    }


    public function fetchSingle($idOrSlug, array $params = [], array $overrideParams = []) : ?array
    {
        return $this->getClient()->fetchSingle(
            static::getEndpointPath(),
            $idOrSlug,
            array_merge($params, $overrideParams)
        );
    }


    public function fetchCollection(array $params = [], array $overrideParams = []) : Collection
    {
        return $this->getClient()->fetchCollection(
            static::getEndpointPath(),
            array_merge(['ordering' => static::getDefaultOrdering()], $params, $overrideParams)
        );
    }


    public function create(array $data = []) : ?array
    {
        return $this->getClient()->create(static::getEndpointPath(), $data);
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
