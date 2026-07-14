<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Collection;

class UserListsRepository extends AbstractRepository
{
    /**
     * Returns a collection of user lists matching the given IDs.
     *
     * @param array $ids IDs of the user lists to fetch
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of user lists
     */
    public function findByIds(array $ids, array $params = []): Collection
    {
        return $this->fetchCollection(array_merge([
            'id__in' => $ids,
        ], $params));
    }


    public static function getEndpointPath(): string
    {
        return 'user_lists';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'name';
    }
}
