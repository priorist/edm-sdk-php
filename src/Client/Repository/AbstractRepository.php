<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;
use Priorist\EDM\Client\Rest\RestClient;
use Priorist\EDM\Client\Collection;

abstract class AbstractRepository implements Repository
{
    protected RestClient $client;


    public function __construct(RestClient $client)
    {
        $this->setClient($client);
    }


    public function fetchSingle($idOrSlug, array $params = [], array $overrideParams = []): array | null
    {
        return $this->filterItem(
            $this->getClient()->fetchSingle(
                static::getEndpointPath(),
                $idOrSlug,
                array_merge($params, $overrideParams)
            )
        );
    }


    public function fetchCollection(array $params = [], array $overrideParams = []): Collection
    {
        return $this->filterCollection(
            $this->getClient()->fetchCollection(
                static::getEndpointPath(),
                array_merge(['ordering' => static::getDefaultOrdering()], $params, $overrideParams)
            )
        );
    }


    public function create(array $data = []): array | null
    {
        return $this->getClient()->create(static::getEndpointPath(), $data);
    }


    public function setClient(RestClient $client): self
    {
        $this->client = $client;

        return $this;
    }


    public function getClient(): RestClient
    {
        return $this->client;
    }


    /**
     * Filters retrieved collection to not include unwanted fields.
     *
     * @param Collection $collection The collection to filter
     *
     * @return Collection The filtered collection
     */
    protected function filterCollection(Collection $collection): Collection
    {
        foreach ($collection as $key => $item) {
            $collection[$key] = $this->filterItem($item);
        }

        return $collection;
    }


    /**
     * Filters retrieved item to not include unwanted fields.
     *
     * @param array|null $item The item to filter
     *
     * @return array|null The filtered item or null if it was not found
     */
    protected function filterItem(array | null $item): array | null
    {
        return $item; // Default implementation, can be overridden in subclasses
    }


    abstract public static function getEndpointPath(): string;
    abstract protected static function getDefaultOrdering(): string;
}
